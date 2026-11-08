<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Models\StaffMessage;
use App\Models\User;
use App\Models\GeneralSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SendUnreadMessagesDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:notify-unread';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email digest for unread messages that have been waiting 15-30+ minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning for unread messages older than 15 minutes...');

        $cutoffStart = Carbon::now()->subMinutes(15);
        $cutoffEnd = Carbon::now()->subHours(24);

        $companySettings = GeneralSettings::first();
        $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

        // 1. Client Conversation Messages
        $unreadMessages = Message::with(['user', 'conversation'])
            ->where('read', false)
            ->where('created_at', '<=', $cutoffStart)
            ->where('created_at', '>=', $cutoffEnd)
            ->get();

        $recipientsToNotify = [];

        foreach ($unreadMessages as $msg) {
            if (!$msg->conversation) continue;

            $participantIds = DB::table('conversations_user')
                ->where('conversation_id', $msg->conversation_id)
                ->where('user_id', '!=', $msg->user_id)
                ->pluck('user_id')
                ->toArray();

            foreach ($participantIds as $pId) {
                if (!isset($recipientsToNotify[$pId])) {
                    $recipientsToNotify[$pId] = [
                        'user' => User::find($pId),
                        'count' => 0,
                        'latest_sender' => $msg->user ? $msg->user->name : 'Counsel',
                        'latest_text' => $msg->text ?: $msg->message ?: 'Document attachment',
                        'latest_time' => $msg->created_at,
                        'url' => route('client.conversation.index'),
                    ];
                }
                $recipientsToNotify[$pId]['count']++;
            }
        }

        // 2. Staff Messages
        $unreadStaffMsgs = StaffMessage::with(['sender', 'staff', 'officer'])
            ->where('read', false)
            ->where('created_at', '<=', $cutoffStart)
            ->where('created_at', '>=', $cutoffEnd)
            ->get();

        foreach ($unreadStaffMsgs as $sMsg) {
            $recipientId = ($sMsg->sender_id == $sMsg->staff_user_id) ? $sMsg->officer_user_id : $sMsg->staff_user_id;
            if (!$recipientId) continue;

            if (!isset($recipientsToNotify[$recipientId])) {
                $recipientsToNotify[$recipientId] = [
                    'user' => User::find($recipientId),
                    'count' => 0,
                    'latest_sender' => $sMsg->sender ? $sMsg->sender->name : 'Staff Officer',
                    'latest_text' => $sMsg->message ?: 'Document attachment',
                    'latest_time' => $sMsg->created_at,
                    'url' => route('staff.messages'),
                ];
            }
            $recipientsToNotify[$recipientId]['count']++;
        }

        // 3. Dispatch Emails (throttled to max 1 email every 60 minutes per user)
        $sentCount = 0;
        foreach ($recipientsToNotify as $userId => $info) {
            $user = $info['user'];
            if (!$user || empty($user->email)) continue;

            $throttleKey = "unread_msg_email_sent_user_{$user->id}";
            if (Cache::has($throttleKey)) {
                continue;
            }

            try {
                $count = $info['count'];
                $senderName = $info['latest_sender'];
                $sampleText = \Illuminate\Support\Str::limit($info['latest_text'], 90);
                $url = $info['url'];

                $subject = "💬 You have {$count} unread message" . ($count > 1 ? 's' : '') . " from {$senderName} | {$companyName}";

                $html = "
                <div style='background: #0b0e14; color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif; padding: 30px; border-radius: 12px; max-width: 560px; margin: 0 auto; border: 1px solid #28303f;'>
                    <div style='text-align: center; margin-bottom: 24px;'>
                        <h2 style='color: #fecc56; margin: 0 0 6px 0; font-size: 24px;'>{$companyName}</h2>
                        <p style='color: #94a3b8; font-size: 13px; margin: 0;'>Secure Client & Practitioner Portal</p>
                    </div>
                    <div style='background: #161a23; padding: 26px; border-radius: 10px; border: 1px solid #28303f;'>
                        <h3 style='margin: 0 0 12px 0; color: #ffffff; font-size: 17px; font-weight: 700;'>
                            💬 Unread Messages Waiting
                        </h3>
                        <p style='font-size: 14px; color: #cbd5e1; line-height: 1.5; margin-top: 0;'>
                            Hello <strong>{$user->name}</strong>,
                        </p>
                        <p style='font-size: 13.5px; color: #94a3b8; line-height: 1.6;'>
                            You have <strong>{$count} unread message" . ($count > 1 ? 's' : '') . "</strong> from <strong>{$senderName}</strong> waiting in your secure workspace that have not been read.
                        </p>
                        <div style='background: #11151e; border-left: 3px solid #fecc56; padding: 14px 18px; border-radius: 4px; margin: 20px 0;'>
                            <small style='color: #94a3b8; display: block; font-size: 11px; margin-bottom: 4px;'>Latest Message Preview:</small>
                            <span style='font-size: 13px; color: #e2e8f0; font-style: italic;'>
                                &ldquo;{$sampleText}&rdquo;
                            </span>
                        </div>
                        <div style='text-align: center; margin-top: 26px;'>
                            <a href='{$url}' style='display: inline-block; background: linear-gradient(135deg, #fecc56, #f0a500); color: #000; font-weight: 700; font-size: 14px; text-decoration: none; padding: 12px 28px; border-radius: 8px;'>
                                View &amp; Reply in Portal &rarr;
                            </a>
                        </div>
                    </div>
                    <div style='text-align: center; margin-top: 22px; font-size: 11px; color: #64748b;'>
                        &copy; " . date('Y') . " {$companyName}. All communications are cryptographically protected.
                    </div>
                </div>
                ";

                Mail::html($html, function ($msg) use ($user, $subject) {
                    $msg->to($user->email, $user->name)->subject($subject);
                });

                Cache::put($throttleKey, true, now()->addHours(4));
                $sentCount++;
                $this->info("Dispatched digest to {$user->email} for {$count} unread message(s).");
            } catch (\Throwable $e) {
                $this->warn("Failed to dispatch to {$user->email}: " . $e->getMessage());
            }
        }

        $this->info("Completed. Dispatched {$sentCount} digest email(s).");
    }
}

