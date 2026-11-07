<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\StaffMessage;
use App\Models\GeneralSettings;

class WebRtcCallController extends Controller
{
    /**
     * Auto-ensure call_sessions table exists in MySQL
     */
    protected function ensureTableExists()
    {
        try {
            if (!Schema::hasTable('call_sessions')) {
                Schema::create('call_sessions', function (Blueprint $table) {
                    $table->id();
                    $table->string('call_id', 64)->unique();
                    $table->unsignedBigInteger('caller_id')->index();
                    $table->unsignedBigInteger('recipient_id')->index();
                    $table->string('caller_name', 255)->nullable();
                    $table->string('caller_avatar', 255)->nullable();
                    $table->string('recipient_name', 255)->nullable();
                    $table->string('recipient_avatar', 255)->nullable();
                    $table->string('call_type', 20)->default('audio');
                    $table->string('status', 20)->default('ringing')->index();
                    $table->longText('offer')->nullable();
                    $table->longText('answer')->nullable();
                    $table->longText('caller_candidates')->nullable();
                    $table->longText('callee_candidates')->nullable();
                    $table->string('conversation_slug', 100)->nullable()->index();
                    $table->string('decline_reason', 255)->nullable();
                    $table->unsignedBigInteger('connected_at')->nullable();
                    $table->unsignedBigInteger('ended_at')->nullable();
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {
            // Table might have been created concurrently
        }
    }

    /**
     * Helper to normalize DB row or array into standardized call payload
     */
    protected function formatCallData($call): array
    {
        if (is_object($call)) {
            $offer = $call->offer;
            if (is_string($offer)) {
                $decoded = json_decode($offer, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $offer = $decoded;
                }
            }

            $answer = $call->answer;
            if (is_string($answer)) {
                $decoded = json_decode($answer, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $answer = $decoded;
                }
            }

            $callerCands = $call->caller_candidates;
            if (is_string($callerCands)) {
                $decoded = json_decode($callerCands, true);
                $callerCands = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($callerCands)) {
                $callerCands = [];
            }

            $calleeCands = $call->callee_candidates;
            if (is_string($calleeCands)) {
                $decoded = json_decode($calleeCands, true);
                $calleeCands = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($calleeCands)) {
                $calleeCands = [];
            }

            return [
                'call_id' => $call->call_id,
                'caller_id' => (int)$call->caller_id,
                'caller_name' => $call->caller_name ?: 'Counsel',
                'caller_avatar' => $call->caller_avatar,
                'recipient_id' => (int)$call->recipient_id,
                'recipient_name' => $call->recipient_name,
                'recipient_avatar' => $call->recipient_avatar,
                'call_type' => $call->call_type ?: 'audio',
                'status' => $call->status ?: 'ringing',
                'offer' => $offer,
                'caller_candidates' => $callerCands,
                'answer' => $answer,
                'callee_candidates' => $calleeCands,
                'conversation_slug' => $call->conversation_slug,
                'connected_at' => $call->connected_at ? (int)$call->connected_at : null,
                'ended_at' => $call->ended_at ? (int)$call->ended_at : null,
                'created_at' => $call->created_at ? strtotime((string)$call->created_at) : now()->timestamp,
            ];
        }

        if (is_array($call)) {
            $offer = $call['offer'] ?? null;
            if (is_string($offer)) {
                $decoded = json_decode($offer, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $offer = $decoded;
                }
            }
            $answer = $call['answer'] ?? null;
            if (is_string($answer)) {
                $decoded = json_decode($answer, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $answer = $decoded;
                }
            }
            $call['offer'] = $offer;
            $call['answer'] = $answer;
            if (!isset($call['caller_candidates']) || !is_array($call['caller_candidates'])) {
                $call['caller_candidates'] = [];
            }
            if (!isset($call['callee_candidates']) || !is_array($call['callee_candidates'])) {
                $call['callee_candidates'] = [];
            }
            return $call;
        }

        return [];
    }

    /**
     * Retrieve call session reliably from DB with Cache fallback
     */
    protected function getCallSession(string $callId): ?array
    {
        $this->ensureTableExists();

        // 1. Check MySQL
        try {
            $dbRow = DB::table('call_sessions')->where('call_id', $callId)->first();
            if ($dbRow) {
                $formatted = $this->formatCallData($dbRow);
                Cache::put("call_session_{$callId}", $formatted, 300);
                return $formatted;
            }
        } catch (\Throwable $e) {}

        // 2. Check Cache
        $cached = Cache::get("call_session_{$callId}");
        if ($cached && is_array($cached)) {
            return $this->formatCallData($cached);
        }

        return null;
    }

    /**
     * Initiate a WebRTC Audio or Video Call
     * Restricted to Admins & Supervising Officers
     */
    public function initiate(Request $request)
    {
        $this->ensureTableExists();

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

        $offerInput = $request->input('offer');
        $offerJson = is_array($offerInput) ? json_encode($offerInput) : (string)$offerInput;

        $candsInput = $request->input('candidates', []);
        $candsJson = is_array($candsInput) ? json_encode($candsInput) : json_encode([]);

        $nowTs = now()->timestamp;
        $convoSlug = $request->input('conversation_slug');

        $callerAvatar = $user->profile_photo_url ? asset($user->profile_photo_url) : null;
        $recipientAvatar = $recipient->profile_photo_url ? asset($recipient->profile_photo_url) : null;

        // 1. Insert into MySQL call_sessions table for 100% durable signaling
        try {
            DB::table('call_sessions')->insert([
                'call_id' => $callId,
                'caller_id' => $user->id,
                'recipient_id' => $recipient->id,
                'caller_name' => $user->name ?: 'Assigned Counsel',
                'caller_avatar' => $callerAvatar,
                'recipient_name' => $recipient->name,
                'recipient_avatar' => $recipientAvatar,
                'call_type' => $callType,
                'status' => 'ringing',
                'offer' => $offerJson,
                'answer' => null,
                'caller_candidates' => $candsJson,
                'callee_candidates' => json_encode([]),
                'conversation_slug' => $convoSlug,
                'decline_reason' => null,
                'connected_at' => null,
                'ended_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::error('Failed to insert call_sessions: ' . $e->getMessage());
        }

        $callData = [
            'call_id' => $callId,
            'caller_id' => $user->id,
            'caller_name' => $user->name ?: 'Assigned Counsel',
            'caller_avatar' => $callerAvatar,
            'recipient_id' => $recipient->id,
            'recipient_name' => $recipient->name,
            'recipient_avatar' => $recipientAvatar,
            'call_type' => $callType,
            'status' => 'ringing',
            'offer' => is_array($offerInput) ? $offerInput : json_decode($offerJson, true),
            'caller_candidates' => is_array($candsInput) ? $candsInput : [],
            'answer' => null,
            'callee_candidates' => [],
            'conversation_slug' => $convoSlug,
            'created_at' => $nowTs,
            'connected_at' => null,
            'ended_at' => null,
        ];

        // 2. Cache call session with 300-second TTL
        Cache::put("call_session_{$callId}", $callData, 300);
        Cache::put("user_active_call_{$recipient->id}", $callId, 120);
        Cache::put("user_active_call_{$user->id}", $callId, 300);

        if (!empty($convoSlug)) {
            $conversation = Conversation::where('slug', $convoSlug)->first();
            if ($conversation) {
                Cache::put("active_call_convo_{$conversation->id}", $callData, 300);
            }
        } else {
            Cache::put("active_call_staff_{$recipient->id}", $callData, 300);
        }

        // 3. Telegram Notification
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
        $this->ensureTableExists();

        $user = Auth::user();
        if (!$user) {
            return response()->json(['active_call' => null]);
        }

        $callId = Cache::get("user_active_call_{$user->id}");

        // If cache has no active call key, query MySQL directly for ringing calls targeted at this user
        if (!$callId) {
            try {
                $dbRinging = DB::table('call_sessions')
                    ->where('recipient_id', $user->id)
                    ->where('status', 'ringing')
                    ->where('created_at', '>=', now()->subSeconds(60))
                    ->latest()
                    ->first();

                if ($dbRinging) {
                    $callId = $dbRinging->call_id;
                    Cache::put("user_active_call_{$user->id}", $callId, 60);
                }
            } catch (\Throwable $e) {}
        }

        if (!$callId) {
            $latestUnread = $this->getLatestUnreadMessageForUser($user);
            return response()->json(['active_call' => null, 'latest_unread' => $latestUnread]);
        }

        $callData = $this->getCallSession($callId);

        if (!$callData || in_array($callData['status'], ['ended', 'declined', 'missed', 'cancelled'])) {
            Cache::forget("user_active_call_{$user->id}");
            $latestUnread = $this->getLatestUnreadMessageForUser($user);
            return response()->json(['active_call' => null, 'latest_unread' => $latestUnread]);
        }

        // Auto-expire ringing calls older than 50 seconds as missed
        if ($callData['status'] === 'ringing' && (now()->timestamp - $callData['created_at']) > 50) {
            $this->logCallOutcome($callData, 'missed');
            $callData['status'] = 'missed';

            try {
                DB::table('call_sessions')->where('call_id', $callId)->update([
                    'status' => 'missed',
                    'ended_at' => now()->timestamp,
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {}

            Cache::put("call_session_{$callId}", $callData, 15);
            Cache::forget("user_active_call_{$callData['recipient_id']}");
            Cache::forget("user_active_call_{$callData['caller_id']}");
            $latestUnread = $this->getLatestUnreadMessageForUser($user);
            return response()->json(['active_call' => null, 'latest_unread' => $latestUnread]);
        }

        $latestUnread = $this->getLatestUnreadMessageForUser($user);
        return response()->json(['active_call' => $callData, 'latest_unread' => $latestUnread]);
    }

    /**
     * WebRTC Signaling: SDP Answer and ICE Candidates Exchange
     */
    public function signal(Request $request)
    {
        $this->ensureTableExists();

        $request->validate([
            'call_id' => 'required|string',
            'action' => 'required|string|in:answer,candidate,candidates_batch,poll',
        ]);

        $callId = $request->input('call_id');
        $callData = $this->getCallSession($callId);

        // Never return 404 to avoid console error spam; gracefully return status: ended
        if (!$callData) {
            return response()->json([
                'status' => 'ended',
                'message' => 'Call session not found or ended.'
            ], 200);
        }

        if (in_array($callData['status'], ['ended', 'declined', 'cancelled', 'missed'])) {
            return response()->json([
                'status' => 'ended',
                'call' => $callData
            ], 200);
        }

        $action = $request->input('action');
        $user = Auth::user();

        if ($action === 'answer') {
            $answer = $request->input('answer');
            $callData['answer'] = is_array($answer) ? $answer : json_decode($answer, true);
            $callData['status'] = 'connected';
            $callData['connected_at'] = now()->timestamp;

            if ($request->filled('candidates')) {
                $candidates = $request->input('candidates');
                $newCands = is_array($candidates) ? $candidates : [$candidates];
                $callData['callee_candidates'] = array_merge($callData['callee_candidates'] ?? [], $newCands);
            }

            // Persist to MySQL
            try {
                DB::table('call_sessions')->where('call_id', $callId)->update([
                    'answer' => is_array($answer) ? json_encode($answer) : (string)$answer,
                    'status' => 'connected',
                    'connected_at' => $callData['connected_at'],
                    'callee_candidates' => json_encode($callData['callee_candidates']),
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {
                \Log::error('Failed to update call_sessions on answer: ' . $e->getMessage());
            }

            // Cache active session up to 2 hours
            Cache::put("call_session_{$callId}", $callData, 7200);
            Cache::put("user_active_call_{$callData['recipient_id']}", $callId, 7200);
            Cache::put("user_active_call_{$callData['caller_id']}", $callId, 7200);

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

            return response()->json([
                'status' => 'ok',
                'server_time' => now()->timestamp,
                'call' => $callData
            ]);

        } elseif ($action === 'candidate') {
            $candidate = $request->input('candidate');
            if ($candidate) {
                $isCaller = ($user && $user->id === $callData['caller_id']);
                if ($isCaller) {
                    $callData['caller_candidates'][] = $candidate;
                } else {
                    $callData['callee_candidates'][] = $candidate;
                }

                try {
                    $field = $isCaller ? 'caller_candidates' : 'callee_candidates';
                    DB::table('call_sessions')->where('call_id', $callId)->update([
                        $field => json_encode($isCaller ? $callData['caller_candidates'] : $callData['callee_candidates']),
                        'updated_at' => now(),
                    ]);
                } catch (\Throwable $e) {}

                Cache::put("call_session_{$callId}", $callData, 7200);
            }
            return response()->json(['status' => 'ok']);

        } elseif ($action === 'candidates_batch') {
            $candidates = $request->input('candidates', []);
            if (!empty($candidates) && is_array($candidates)) {
                $isCaller = ($user && $user->id === $callData['caller_id']);
                if ($isCaller) {
                    $callData['caller_candidates'] = array_merge($callData['caller_candidates'] ?? [], $candidates);
                } else {
                    $callData['callee_candidates'] = array_merge($callData['callee_candidates'] ?? [], $candidates);
                }

                try {
                    $field = $isCaller ? 'caller_candidates' : 'callee_candidates';
                    DB::table('call_sessions')->where('call_id', $callId)->update([
                        $field => json_encode($isCaller ? $callData['caller_candidates'] : $callData['callee_candidates']),
                        'updated_at' => now(),
                    ]);
                } catch (\Throwable $e) {}

                Cache::put("call_session_{$callId}", $callData, 7200);
            }
            return response()->json(['status' => 'ok']);

        } elseif ($action === 'poll') {
            return response()->json([
                'status' => 'ok',
                'server_time' => now()->timestamp,
                'call' => $callData
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'server_time' => now()->timestamp,
            'call' => $callData
        ]);
    }

    /**
     * Respond to Call: Accept, Decline, End, or Cancel
     */
    public function respond(Request $request)
    {
        $this->ensureTableExists();

        $request->validate([
            'call_id' => 'required|string',
            'action' => 'required|string|in:accept,decline,end,cancel,missed',
            'reason' => 'nullable|string|max:255',
            'duration_seconds' => 'nullable|integer',
        ]);

        $callId = $request->input('call_id');
        $callData = $this->getCallSession($callId);

        if (!$callData) {
            return response()->json(['status' => 'ok', 'message' => 'Call session already terminated.']);
        }

        $action = $request->input('action');
        $reason = $request->input('reason', '');

        if ($action === 'decline' || $action === 'cancel' || $action === 'missed') {
            $callData['status'] = $action;
            $callData['ended_at'] = now()->timestamp;
            $this->logCallOutcome($callData, $action, $reason);

            try {
                DB::table('call_sessions')->where('call_id', $callId)->update([
                    'status' => $action,
                    'decline_reason' => $reason,
                    'ended_at' => $callData['ended_at'],
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {}

            Cache::put("call_session_{$callId}", $callData, 30);
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

            $clientSec = $request->input('duration_seconds');
            if (is_numeric($clientSec) && (int)$clientSec > 0) {
                $duration = (int)$clientSec;
            } else {
                $duration = $callData['connected_at'] ? max(0, now()->timestamp - (int)$callData['connected_at']) : 0;
            }
            $durationFormatted = gmdate($duration >= 3600 ? 'H:i:s' : 'i:s', $duration);

            $this->logCallOutcome($callData, 'ended', '', $durationFormatted);

            try {
                DB::table('call_sessions')->where('call_id', $callId)->update([
                    'status' => 'ended',
                    'ended_at' => $callData['ended_at'],
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {}

            Cache::put("call_session_{$callId}", $callData, 30);
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
            $this->sendMissedCallEmail($callData, $callType);
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

    /**
     * Dispatch High-Priority Missed Call Email Notification
     */
    protected function sendMissedCallEmail(array $callData, string $callType)
    {
        try {
            $recipient = User::find($callData['recipient_id'] ?? null);
            if (!$recipient || empty($recipient->email)) {
                return;
            }

            $callerName = $callData['caller_name'] ?? 'Your CPA Expert Representative';
            $companySettings = GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');
            $subject = "📞 Missed {$callType} Call from {$callerName} | {$companyName}";

            $callbackUrl = !empty($callData['conversation_slug'])
                ? route('client.conversation.index')
                : route('staff.messages');

            $timeStr = now()->format('M d, Y h:i A T');

            $html = "
            <div style='background: #0b0e14; color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif; padding: 30px; border-radius: 12px; max-width: 560px; margin: 0 auto; border: 1px solid #28303f;'>
                <div style='text-align: center; margin-bottom: 24px;'>
                    <h2 style='color: #fecc56; margin: 0 0 6px 0; font-size: 24px;'>{$companyName}</h2>
                    <p style='color: #94a3b8; font-size: 13px; margin: 0;'>Secure Client & Practitioner Portal</p>
                </div>
                <div style='background: #161a23; padding: 26px; border-radius: 10px; border: 1px solid #28303f; text-align: left;'>
                    <div style='display: flex; align-items: center; margin-bottom: 16px;'>
                        <div style='width: 44px; height: 44px; border-radius: 50%; background: rgba(239, 68, 68, 0.15); color: #ef4444; font-size: 22px; line-height: 44px; text-align: center; margin-right: 14px;'>
                            📞
                        </div>
                        <div>
                            <h3 style='margin: 0; color: #ffffff; font-size: 17px; font-weight: 700;'>Missed {$callType} Call</h3>
                            <p style='margin: 0; color: #94a3b8; font-size: 12px;'>{$timeStr}</p>
                        </div>
                    </div>
                    <p style='font-size: 14px; color: #cbd5e1; line-height: 1.5;'>
                        Hello <strong>{$recipient->name}</strong>,
                    </p>
                    <p style='font-size: 13.5px; color: #94a3b8; line-height: 1.6;'>
                        <strong>{$callerName}</strong> attempted to reach you via encrypted in-portal {$callType} call regarding your active file.
                    </p>
                    <div style='background: #11151e; border-left: 3px solid #fecc56; padding: 14px 18px; border-radius: 4px; margin: 20px 0;'>
                        <span style='font-size: 13px; color: #e2e8f0;'>
                            Please log in to your secure workspace to view pending case updates or return the call.
                        </span>
                    </div>
                    <div style='text-align: center; margin-top: 26px;'>
                        <a href='{$callbackUrl}' style='display: inline-block; background: linear-gradient(135deg, #fecc56, #f0a500); color: #000; font-weight: 700; font-size: 14px; text-decoration: none; padding: 12px 28px; border-radius: 8px;'>
                            Return Call / View Conversation &rarr;
                        </a>
                    </div>
                </div>
                <div style='text-align: center; margin-top: 22px; font-size: 11px; color: #64748b;'>
                    &copy; " . date('Y') . " {$companyName}. All communications are cryptographically protected.
                </div>
            </div>
            ";

            \Illuminate\Support\Facades\Mail::html($html, function ($msg) use ($recipient, $subject) {
                $msg->to($recipient->email, $recipient->name)->subject($subject);
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Missed call email dispatch failed: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve Latest Unread Message for Background & Inactive-Tab Notifications
     */
    protected function getLatestUnreadMessageForUser($user)
    {
        if (!$user) return null;

        try {
            // Check client conversations
            $userConvoIds = DB::table('conversations_user')->where('user_id', $user->id)->pluck('conversation_id')->toArray();
            if (!empty($userConvoIds)) {
                $msg = Message::with(['user', 'conversation'])
                    ->whereIn('conversation_id', $userConvoIds)
                    ->where('user_id', '!=', $user->id)
                    ->where('read', false)
                    ->where('created_at', '>=', now()->subHours(12))
                    ->latest()
                    ->first();

                if ($msg) {
                    $senderName = $msg->user ? $msg->user->name : 'Counsel';
                    $url = $user->hasRole('admin')
                        ? route('admin.conversation.view-chat', $msg->conversation->slug ?? '')
                        : route('client.conversation.index');

                    return [
                        'id' => 'msg_' . $msg->id,
                        'sender_name' => $senderName,
                        'text' => \Illuminate\Support\Str::limit($msg->text ?: $msg->message ?: 'Sent an attachment', 90),
                        'time' => $msg->created_at->format('h:i A'),
                        'url' => $url,
                        'type' => 'chat'
                    ];
                }
            }

            // Check staff messages
            $staffMsg = StaffMessage::with(['sender'])
                ->where(function($q) use ($user) {
                    $q->where('staff_user_id', $user->id)->orWhere('officer_user_id', $user->id);
                })
                ->where('sender_id', '!=', $user->id)
                ->where('read', false)
                ->where('created_at', '>=', now()->subHours(12))
                ->latest()
                ->first();

            if ($staffMsg) {
                $senderName = $staffMsg->sender ? $staffMsg->sender->name : 'Staff Officer';
                $url = route('staff.messages');
                return [
                    'id' => 'staff_msg_' . $staffMsg->id,
                    'sender_name' => $senderName,
                    'text' => \Illuminate\Support\Str::limit($staffMsg->message ?: 'Sent an attachment', 90),
                    'time' => $staffMsg->created_at->format('h:i A'),
                    'url' => $url,
                    'type' => 'staff'
                ];
            }
        } catch (\Throwable $e) {}

        return null;
    }
}
