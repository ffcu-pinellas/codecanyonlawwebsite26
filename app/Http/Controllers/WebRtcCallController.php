<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\StaffMessage;
use App\Models\GeneralSettings;

class WebRtcCallController extends Controller
{
    /**
     * Initiate a WebRTC Audio or Video Call
     * Restricted to Admins & Supervising Officers
     */
    public function initiate(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // Strict role check: Only Admins and Supervising Officers can initiate calls
        $isAdminOrSupervisor = $user->hasAnyRole(['admin', 'supervisor']) || $user->is_admin;
        if (!$isAdminOrSupervisor) {
            return response()->json([
                'error' => 'Permission denied. Only practice supervisors and administrators can initiate direct calls.'
            ], 403);
        }

        $request->validate([
            'recipient_id' => 'required|integer',
            'call_type' => 'required|string|in:audio,video',
            'offer' => 'required',
            'candidates' => 'nullable|array',
            'conversation_slug' => 'nullable|string',
        ]);

        $recipient = User::findOrFail($request->recipient_id);

        $callId = 'call_' . uniqid() . '_' . rand(100, 999);
        $callType = $request->input('call_type', 'audio');

        $callData = [
            'call_id' => $callId,
            'caller_id' => $user->id,
            'caller_name' => $user->name ?: 'Assigned Counsel',
            'caller_avatar' => $user->profile_photo_url ? asset($user->profile_photo_url) : null,
            'recipient_id' => $recipient->id,
            'recipient_name' => $recipient->name,
            'recipient_avatar' => $recipient->profile_photo_url ? asset($recipient->profile_photo_url) : null,
            'call_type' => $callType, // 'audio' or 'video'
            'status' => 'ringing', // 'ringing', 'connected', 'ended', 'declined', 'missed', 'cancelled'
            'offer' => $request->input('offer'),
            'caller_candidates' => $request->input('candidates', []),
            'answer' => null,
            'callee_candidates' => [],
            'conversation_slug' => $request->input('conversation_slug'),
            'created_at' => now()->timestamp,
            'connected_at' => null,
            'ended_at' => null,
        ];

        // Cache call session with 120-second ringing TTL
        Cache::put("call_session_{$callId}", $callData, 120);
        Cache::put("user_active_call_{$recipient->id}", $callId, 60);
        Cache::put("user_active_call_{$user->id}", $callId, 120);

        if ($request->filled('conversation_slug')) {
            $conversation = Conversation::where('slug', $request->conversation_slug)->first();
            if ($conversation) {
                Cache::put("active_call_convo_{$conversation->id}", $callData, 120);
            }
        } else {
            // Staff call cache key
            Cache::put("active_call_staff_{$recipient->id}", $callData, 120);
        }

        // Telegram Notification
        $icon = ($callType === 'video') ? '🎥' : '📞';
        $typeLabel = ($callType === 'video') ? 'Video' : 'Voice';
        $telMsg = "{$icon} <b>{$typeLabel} Call Initiated</b>\n"
                . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                . "👤 <b>Caller:</b> " . htmlspecialchars($user->name) . "\n"
                . "👥 <b>Recipient:</b> " . htmlspecialchars($recipient->name) . "\n"
                . "📱 <b>Type:</b> In-App Direct {$typeLabel} Call\n"
                . "⏳ <b>Status:</b> Ringing on recipient device...";
        GeneralSettings::sendTelegramNotification($telMsg);

        return response()->json([
            'status' => 'ok',
            'call_id' => $callId,
            'call' => $callData
        ]);
    }

    /**
     * Lightweight Global Call Check (polled every 3 seconds across master layouts)
     */
    public function check(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['active_call' => null]);
        }

        $callId = Cache::get("user_active_call_{$user->id}");
        if (!$callId) {
            return response()->json(['active_call' => null]);
        }

        $callData = Cache::get("call_session_{$callId}");
        if (!$callData || in_array($callData['status'], ['ended', 'declined', 'missed', 'cancelled'])) {
            Cache::forget("user_active_call_{$user->id}");
            return response()->json(['active_call' => null]);
        }

        // Auto-expire ringing calls older than 50 seconds as missed
        if ($callData['status'] === 'ringing' && (now()->timestamp - $callData['created_at']) > 50) {
            $this->logCallOutcome($callData, 'missed');
            $callData['status'] = 'missed';
            Cache::put("call_session_{$callId}", $callData, 15);
            Cache::forget("user_active_call_{$callData['recipient_id']}");
            Cache::forget("user_active_call_{$callData['caller_id']}");
            return response()->json(['active_call' => null]);
        }

        return response()->json(['active_call' => $callData]);
    }

    /**
     * WebRTC Signaling: SDP Answer and ICE Candidates Exchange
     */
    public function signal(Request $request)
    {
        $request->validate([
            'call_id' => 'required|string',
            'action' => 'required|string|in:answer,candidate,poll',
        ]);

        $callId = $request->input('call_id');
        $callData = Cache::get("call_session_{$callId}");

        if (!$callData) {
            return response()->json(['status' => 'error', 'message' => 'Call session expired or ended.'], 404);
        }

        $action = $request->input('action');
        $user = Auth::user();

        if ($action === 'answer') {
            $callData['answer'] = $request->input('answer');
            $callData['status'] = 'connected';
            $callData['connected_at'] = now()->timestamp;

            if ($request->filled('candidates')) {
                $candidates = $request->input('candidates');
                $callData['callee_candidates'] = array_merge($callData['callee_candidates'] ?? [], is_array($candidates) ? $candidates : [$candidates]);
            }

            // Extend session TTL during active conversation (up to 2 hours)
            Cache::put("call_session_{$callId}", $callData, 7200);
            Cache::put("user_active_call_{$callData['recipient_id']}", $callId, 7200);
            Cache::put("user_active_call_{$callData['caller_id']}", $callId, 7200);

            // Update conversation active call cache
            if (!empty($callData['conversation_slug'])) {
                $convo = Conversation::where('slug', $callData['conversation_slug'])->first();
                if ($convo) {
                    Cache::put("active_call_convo_{$convo->id}", $callData, 7200);
                }
            } else {
                Cache::put("active_call_staff_{$callData['recipient_id']}", $callData, 7200);
            }

            $icon = ($callData['call_type'] === 'video') ? '🎥' : '📞';
            GeneralSettings::sendTelegramNotification("{$icon} <b>Call Connected</b>\n👤 {$callData['recipient_name']} answered the call with {$callData['caller_name']}. Live session in progress.");

            return response()->json(['status' => 'ok', 'call' => $callData]);
        } elseif ($action === 'candidate') {
            $candidate = $request->input('candidate');
            if ($candidate) {
                if ($user->id === $callData['caller_id']) {
                    $callData['caller_candidates'][] = $candidate;
                } else {
                    $callData['callee_candidates'][] = $candidate;
                }
                Cache::put("call_session_{$callId}", $callData, 7200);
            }
            return response()->json(['status' => 'ok']);
        } elseif ($action === 'poll') {
            return response()->json(['status' => 'ok', 'call' => $callData]);
        }

        return response()->json(['status' => 'ok', 'call' => $callData]);
    }

    /**
     * Respond to Call: Accept, Decline, End, or Cancel
     */
    public function respond(Request $request)
    {
        $request->validate([
            'call_id' => 'required|string',
            'action' => 'required|string|in:accept,decline,end,cancel,missed',
            'reason' => 'nullable|string|max:255',
        ]);

        $callId = $request->input('call_id');
        $callData = Cache::get("call_session_{$callId}");

        if (!$callData) {
            return response()->json(['status' => 'ok', 'message' => 'Call session already terminated.']);
        }

        $action = $request->input('action');
        $reason = $request->input('reason', '');

        if ($action === 'decline' || $action === 'cancel' || $action === 'missed') {
            $callData['status'] = $action;
            $callData['ended_at'] = now()->timestamp;
            $this->logCallOutcome($callData, $action, $reason);

            Cache::put("call_session_{$callId}", $callData, 15);
            Cache::forget("user_active_call_{$callData['recipient_id']}");
            Cache::forget("user_active_call_{$callData['caller_id']}");

            if (!empty($callData['conversation_slug'])) {
                $convo = Conversation::where('slug', $callData['conversation_slug'])->first();
                if ($convo) Cache::forget("active_call_convo_{$convo->id}");
            } else {
                Cache::forget("active_call_staff_{$callData['recipient_id']}");
            }

            return response()->json(['status' => 'ok', 'call' => $callData]);
        } elseif ($action === 'end') {
            $callData['status'] = 'ended';
            $callData['ended_at'] = now()->timestamp;

            $duration = $callData['connected_at'] ? (now()->timestamp - $callData['connected_at']) : 0;
            $durationFormatted = gmdate($duration >= 3600 ? 'H:i:s' : 'i:s', $duration);

            $this->logCallOutcome($callData, 'ended', '', $durationFormatted);

            Cache::put("call_session_{$callId}", $callData, 15);
            Cache::forget("user_active_call_{$callData['recipient_id']}");
            Cache::forget("user_active_call_{$callData['caller_id']}");

            if (!empty($callData['conversation_slug'])) {
                $convo = Conversation::where('slug', $callData['conversation_slug'])->first();
                if ($convo) Cache::forget("active_call_convo_{$convo->id}");
            } else {
                Cache::forget("active_call_staff_{$callData['recipient_id']}");
            }

            return response()->json(['status' => 'ok', 'duration' => $durationFormatted]);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Client / Staff Request a Call from Counsel / Supervisor
     */
    public function requestCall(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $type = $request->input('call_type', 'audio');
        $note = trim($request->input('note', ''));
        $typeLabel = ($type === 'video') ? 'Video' : 'Voice';
        $icon = ($type === 'video') ? '🎥' : '📞';

        $convoSlug = $request->input('conversation_slug');
        if ($convoSlug) {
            $convo = Conversation::where('slug', $convoSlug)->first();
            if ($convo) {
                Message::create([
                    'conversation_id' => $convo->id,
                    'user_id' => $user->id,
                    'text' => "{$icon} Client requested a {$typeLabel} Call" . ($note ? ": {$note}" : "."),
                    'message' => "{$icon} Client requested a {$typeLabel} Call" . ($note ? ": {$note}" : "."),
                    'read' => false,
                ]);
            }
        } else {
            // Staff call request
            $staffDetail = $user->staffDetail;
            $officerId = ($staffDetail && $staffDetail->officer_user_id) ? $staffDetail->officer_user_id : 1;
            StaffMessage::create([
                'staff_user_id' => $user->id,
                'officer_user_id' => $officerId,
                'sender_id' => $user->id,
                'message' => "{$icon} Staff requested a {$typeLabel} Call" . ($note ? ": {$note}" : "."),
                'read' => false,
            ]);
        }

        // Telegram Notification
        $telMsg = "{$icon} <b>{$typeLabel} Call Requested</b>\n"
                . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                . "👤 <b>Requested by:</b> " . htmlspecialchars($user->name) . "\n"
                . "📝 <b>Note:</b> " . htmlspecialchars($note ?: 'Live conversation requested') . "\n"
                . "⚡ <i>Please log in to the portal to initiate the call.</i>";
        GeneralSettings::sendTelegramNotification($telMsg);

        return response()->json([
            'status' => 'ok',
            'message' => "{$typeLabel} call request sent to assigned counsel."
        ]);
    }

    /**
     * Helper to log call outcomes into thread and Telegram
     */
    protected function logCallOutcome(array $callData, string $outcome, string $reason = '', string $duration = '')
    {
        $callType = ucfirst($callData['call_type'] ?? 'voice');
        $icon = ($callData['call_type'] === 'video') ? '🎥' : '📞';

        if ($outcome === 'ended') {
            $msgText = "{$icon} {$callType} Call Ended • {$duration}";
            $telText = "{$icon} <b>{$callType} Call Ended</b>\n👥 <b>Participants:</b> {$callData['caller_name']} & {$callData['recipient_name']}\n⏱ <b>Duration:</b> {$duration}";
        } elseif ($outcome === 'decline') {
            $msgText = "{$icon} {$callType} Call Declined" . ($reason ? " ({$reason})" : "");
            $telText = "⚠️ <b>{$callType} Call Declined</b>\n👥 Recipient: {$callData['recipient_name']}\n📝 Reason: " . ($reason ?: 'User unavailable');
        } elseif ($outcome === 'cancel') {
            $msgText = "{$icon} {$callType} Call Cancelled";
            $telText = "ℹ️ <b>{$callType} Call Cancelled</b> by {$callData['caller_name']}.";
        } elseif ($outcome === 'missed') {
            $msgText = "{$icon} Missed {$callType} Call";
            $telText = "⚠️ <b>Missed {$callType} Call</b>\nRecipient {$callData['recipient_name']} did not answer in time.";
        } else {
            return;
        }

        // Post to Client conversation or Staff message thread
        if (!empty($callData['conversation_slug'])) {
            $convo = Conversation::where('slug', $callData['conversation_slug'])->first();
            if ($convo) {
                Message::create([
                    'conversation_id' => $convo->id,
                    'user_id' => $callData['caller_id'],
                    'text' => $msgText,
                    'message' => $msgText,
                    'read' => true,
                ]);
            }
        } elseif (!empty($callData['recipient_id'])) {
            StaffMessage::create([
                'staff_user_id' => $callData['recipient_id'],
                'officer_user_id' => $callData['caller_id'],
                'sender_id' => $callData['caller_id'],
                'message' => $msgText,
                'read' => true,
            ]);
        }

        GeneralSettings::sendTelegramNotification($telText);
    }
}

