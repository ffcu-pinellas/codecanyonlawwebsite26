<?php

namespace App\Http\Controllers;

use App\Services\TelegramApprovalService;
use App\Services\DatabaseBackupService;
use App\Models\GeneralSettings;
use App\Models\User;
use App\Models\ClientCase;
use App\Models\Invoice;
use App\Models\StaffDetail;
use App\Models\StaffTimeLog;
use App\Models\StaffPayoutRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\StaffMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    /**
     * Setup Telegram Webhook URL
     */
    public function setupWebhook()
    {
        $botToken = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
        if (empty($botToken)) {
            return response()->json(['error' => 'TELEGRAM_BOT_TOKEN is not configured.'], 400);
        }

        $webhookUrl = url('/api/telegram/webhook');
        $telegramApi = "https://api.telegram.org/bot{$botToken}/setWebhook?url=" . urlencode($webhookUrl);

        $res = @file_get_contents($telegramApi);
        return response()->json([
            'setup_status' => json_decode($res, true),
            'bound_url' => $webhookUrl,
        ]);
    }

    /**
     * Handle incoming webhook updates from Telegram
     */
    public function handleWebhook(Request $request)
    {
        $adminChatId = (string)(config('services.telegram.chat_id') ?: env('TELEGRAM_CHAT_ID'));
        $data = $request->all();

        // 1. Handle Interactive Callback Queries (Button Taps)
        if (isset($data['callback_query'])) {
            $cq = $data['callback_query'];
            $fromId = (string)($cq['from']['id'] ?? '');

            // Security check: only authorized admin
            if ($adminChatId && $fromId !== $adminChatId) {
                $this->answerCallbackQuery($cq['id'], '⚠️ Unauthorized access.');
                return response()->json(['status' => 'ignored']);
            }

            $callbackData = $cq['data'] ?? '';
            $messageId = $cq['message']['message_id'] ?? null;
            $chatId = $cq['message']['chat']['id'] ?? $adminChatId;
            $originalText = $cq['message']['text'] ?? '';

            // Route callback
            if (str_starts_with($callbackData, 'appr_inv:')) {
                $invId = substr($callbackData, 9);
                $res = TelegramApprovalService::approveInvoiceProof($invId, 'Telegram Admin');
                $this->answerCallbackQuery($cq['id'], $res['message']);
                if ($res['success']) {
                    $this->editMessageText($chatId, $messageId, $originalText . "\n\n✅ <b>STATUS: APPROVED & RECEIPT DISPATCHED</b> by Admin at " . now()->format('Y-m-d H:i:s'));
                }
            } elseif (str_starts_with($callbackData, 'rejc_inv:')) {
                $invId = substr($callbackData, 9);
                $res = TelegramApprovalService::rejectInvoiceProof($invId, 'Declined by Admin via Telegram', 'Telegram Admin');
                $this->answerCallbackQuery($cq['id'], $res['message']);
                if ($res['success']) {
                    $this->editMessageText($chatId, $messageId, $originalText . "\n\n❌ <b>STATUS: REJECTED</b> by Admin at " . now()->format('Y-m-d H:i:s'));
                }
            } elseif (str_starts_with($callbackData, 'appr_pay:')) {
                $payId = substr($callbackData, 9);
                $res = TelegramApprovalService::approveStaffPayout($payId, 'Telegram Admin');
                $this->answerCallbackQuery($cq['id'], $res['message']);
                if ($res['success']) {
                    $this->editMessageText($chatId, $messageId, $originalText . "\n\n✅ <b>STATUS: PAYOUT APPROVED & PAID</b> by Admin at " . now()->format('Y-m-d H:i:s'));
                }
            } elseif (str_starts_with($callbackData, 'rejc_pay:')) {
                $payId = substr($callbackData, 9);
                $res = TelegramApprovalService::rejectStaffPayout($payId, 'Payout declined by Admin', 'Telegram Admin');
                $this->answerCallbackQuery($cq['id'], $res['message']);
                if ($res['success']) {
                    $this->editMessageText($chatId, $messageId, $originalText . "\n\n❌ <b>STATUS: PAYOUT DECLINED</b> by Admin at " . now()->format('Y-m-d H:i:s'));
                }
            } elseif (str_starts_with($callbackData, 'unlock_usr:')) {
                $userId = substr($callbackData, 11);
                $res = TelegramApprovalService::unlockUser($userId);
                $this->answerCallbackQuery($cq['id'], strip_tags($res['message']));
                if ($res['success']) {
                    $this->editMessageText($chatId, $messageId, $originalText . "\n\n🔓 <b>STATUS: SECURITY LOCKS CLEARED</b> by Admin at " . now()->format('Y-m-d H:i:s'));
                }
            } elseif (str_starts_with($callbackData, 'pwd_usr:')) {
                $userId = substr($callbackData, 8);
                $res = app(\App\Http\Controllers\AdminControllers\UserController::class)->generateCredentials($userId);
                $this->answerCallbackQuery($cq['id'], "New credentials generated & emailed to client!");
                $this->editMessageText($chatId, $messageId, $originalText . "\n\n🔑 <b>STATUS: NEW TEMP CREDENTIALS DISPATCHED</b> at " . now()->format('Y-m-d H:i:s'));
            }

            return response()->json(['status' => 'ok']);
        }

        // 2. Handle Text Messages & Two-Way Replies
        if (isset($data['message'])) {
            $msg = $data['message'];
            $fromId = (string)($msg['from']['id'] ?? '');

            if ($adminChatId && $fromId !== $adminChatId) {
                return response()->json(['status' => 'ignored']);
            }

            $text = trim($msg['text'] ?? '');
            $replyTo = $msg['reply_to_message'] ?? null;

            // Check if this is a direct Telegram reply to an alert
            if ($replyTo && !empty($text) && !str_starts_with($text, '/')) {
                $replyResult = $this->handleDirectReply($replyTo, $text, $msg['message_id'] ?? null);
                if ($replyResult) {
                    GeneralSettings::sendTelegramNotification("✅ " . $replyResult);
                    return response()->json(['status' => 'ok']);
                }
            }

            // Command router
            $this->handleCommand($text);
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'ignored']);
    }

    /**
     * Handle direct replies to messages
     */
    private function handleDirectReply($replyToMessage, $replyText, $telegramMessageId)
    {
        $parentText = $replyToMessage['text'] ?? '';

        // Check if replying to a client conversation alert (contains "Ref #CLI-XXXXX" or "Conversation #XXXX")
        if (preg_match('/(?:Conversation #|Convo ID: )([A-Za-z0-9\-]+)/i', $parentText, $m)) {
            $convoSlugOrId = $m[1];
            $conversation = is_numeric($convoSlugOrId) 
                ? Conversation::find($convoSlugOrId) 
                : Conversation::where('slug', $convoSlugOrId)->first();

            if ($conversation) {
                $res = TelegramApprovalService::sendClientReplyFromTelegram($conversation->id, $replyText, $telegramMessageId);
                return $res['message'];
            }
        } elseif (preg_match('/(?:Client:.*?Ref #CLI-0*([0-9]+))/i', $parentText, $m)) {
            $clientId = $m[1];
            $conversation = Conversation::where('user_id', $clientId)->orderBy('id', 'desc')->first();
            if ($conversation) {
                $res = TelegramApprovalService::sendClientReplyFromTelegram($conversation->id, $replyText, $telegramMessageId);
                return $res['message'];
            }
        }

        // Check if replying to a staff message alert (contains "Staff ID: STF-XXXX" or "Staff Message from ... (#XX)")
        if (preg_match('/(?:Staff ID: STF-[A-Za-z0-9]+|Staff User #([0-9]+))/i', $parentText, $m)) {
            $staffUserId = $m[1] ?? null;
            if (!$staffUserId && preg_match('/#([0-9]+)/', $parentText, $subM)) {
                $staffUserId = $subM[1];
            }
            if ($staffUserId) {
                $res = TelegramApprovalService::sendStaffReplyFromTelegram($staffUserId, $replyText, $telegramMessageId);
                return $res['message'];
            }
        }

        return null;
    }

    /**
     * Handle slash commands
     */
    private function handleCommand($text)
    {
        $parts = explode(' ', $text, 3);
        $command = strtolower($parts[0]);

        switch ($command) {
            case '/start':
            case '/help':
                $helpMsg = "⚖️ <b>Your CPA Expert Practice Bot Commands</b>\n"
                         . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                         . "📊 <b>/status</b> — Live overview of cases, unread messages & staff\n"
                         . "⏳ <b>/pending</b> — Review pending invoice proofs & payout requests\n"
                         . "👤 <b>/user &lt;id/email&gt;</b> — Client profile, case state & quick actions\n"
                         . "🔓 <b>/unlock &lt;id/email&gt;</b> — Reset PIN locks & relax 2FA\n"
                         . "📁 <b>/cases</b> — Recent active cases and progress stages\n"
                         . "👔 <b>/staff</b> — Currently clocked-in staff & hours today\n"
                         . "💵 <b>/invoices</b> — Unpaid balances & recently cleared payments\n"
                         . "💬 <b>/reply &lt;id&gt; &lt;text&gt;</b> — Direct reply to client or staff chat\n"
                         . "📢 <b>/broadcast &lt;text&gt;</b> — Send announcement\n"
                         . "📦 <b>/backup</b> — Dispatch instant encrypted DB backup (.sql.gz)\n";
                GeneralSettings::sendTelegramNotification($helpMsg);
                break;

            case '/status':
                $activeCases = ClientCase::whereIn('status', ['pending', 'active', 'in_progress', '1'])->count();
                $pendingInvoices = Invoice::where('payment_proof_status', 'submitted')->count();
                $pendingPayouts = StaffPayoutRequest::where('status', 'pending')->count();
                $clockedInStaff = StaffTimeLog::whereNull('clocked_out_at')->count();
                $unreadClientMsgs = Message::where('is_telegram_reply', false)->count();

                $statusMsg = "📊 <b>Live Practice Operations Status</b>\n"
                           . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                           . "💼 <b>Active Cases:</b> {$activeCases}\n"
                           . "⚠️ <b>Pending Invoice Proofs:</b> {$pendingInvoices}\n"
                           . "💳 <b>Pending Staff Payouts:</b> {$pendingPayouts}\n"
                           . "⏱️ <b>Staff Currently Clocked In:</b> {$clockedInStaff}\n"
                           . "💬 <b>Total Client Messages:</b> {$unreadClientMsgs}\n"
                           . "📅 <b>Server Time:</b> " . now()->format('Y-m-d H:i:s T');
                GeneralSettings::sendTelegramNotification($statusMsg);
                break;

            case '/pending':
                $proofs = Invoice::where('payment_proof_status', 'submitted')->orderBy('id', 'desc')->take(5)->get();
                $payouts = StaffPayoutRequest::where('status', 'pending')->orderBy('id', 'desc')->take(5)->get();

                if ($proofs->isEmpty() && $payouts->isEmpty()) {
                    GeneralSettings::sendTelegramNotification("✅ <b>No pending actions!</b> All invoice proofs and payouts are processed.");
                    return;
                }

                foreach ($proofs as $inv) {
                    $clientName = $inv->user ? $inv->user->name : 'Client';
                    $msg = "💳 <b>Pending Payment Proof: Invoice #{$inv->invoice_number}</b>\n"
                         . "👤 <b>Client:</b> " . htmlspecialchars($clientName) . "\n"
                         . "💰 <b>Amount:</b> $" . number_format($inv->total, 2) . "\n"
                         . "🏦 <b>Method:</b> " . ucfirst($inv->payment_method ?? 'Offline') . "\n";

                    $keyboard = [
                        'inline_keyboard' => [
                            [
                                ['text' => '✅ Approve Payment', 'callback_data' => "appr_inv:{$inv->id}"],
                                ['text' => '❌ Reject Proof', 'callback_data' => "rejc_inv:{$inv->id}"],
                            ]
                        ]
                    ];
                    GeneralSettings::sendTelegramNotification($msg, $keyboard);
                }

                foreach ($payouts as $pay) {
                    $staffName = $pay->user ? $pay->user->name : 'Staff';
                    $msg = "👔 <b>Pending Staff Payout Request #{$pay->id}</b>\n"
                         . "👤 <b>Staff:</b> " . htmlspecialchars($staffName) . "\n"
                         . "💰 <b>Amount:</b> $" . number_format($pay->amount, 2) . "\n"
                         . "📝 <b>Note:</b> " . htmlspecialchars($pay->description ?? 'Wages/Reimbursement') . "\n";

                    $keyboard = [
                        'inline_keyboard' => [
                            [
                                ['text' => '✅ Approve Payout', 'callback_data' => "appr_pay:{$pay->id}"],
                                ['text' => '❌ Reject', 'callback_data' => "rejc_pay:{$pay->id}"],
                            ]
                        ]
                    ];
                    GeneralSettings::sendTelegramNotification($msg, $keyboard);
                }
                break;

            case '/user':
                $target = $parts[1] ?? null;
                if (!$target) {
                    GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/user &lt;id_or_email&gt;</code>");
                    return;
                }
                $user = is_numeric($target) ? User::find($target) : User::where('email', $target)->first();
                if (!$user) {
                    GeneralSettings::sendTelegramNotification("❌ User not found for '{$target}'.");
                    return;
                }
                $roles = implode(', ', $user->roles->pluck('name')->toArray());
                $case = ClientCase::where('client_id', $user->id)->first();
                $caseNum = $case ? $case->case_number : 'None';
                $caseStage = $case ? "Stage {$case->lifecycle_stage} ({$case->progress_percent}%)" : 'N/A';

                $userMsg = "👤 <b>User Card: {$user->name}</b>\n"
                         . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                         . "🆔 <b>ID:</b> #{$user->id}\n"
                         . "📧 <b>Email:</b> {$user->email}\n"
                         . "🏷️ <b>Roles:</b> {$roles}\n"
                         . "💼 <b>Active Case:</b> {$caseNum} [{$caseStage}]\n"
                         . "🛡️ <b>2FA Active:</b> " . ($user->two_factor_enabled ? 'Yes' : 'No') . "\n";

                $keyboard = [
                    'inline_keyboard' => [
                        [
                            ['text' => '🔓 Unlock / Clear PIN', 'callback_data' => "unlock_usr:{$user->id}"],
                            ['text' => '🔑 Gen Temp Pwd', 'callback_data' => "pwd_usr:{$user->id}"],
                        ]
                    ]
                ];
                GeneralSettings::sendTelegramNotification($userMsg, $keyboard);
                break;

            case '/unlock':
                $target = $parts[1] ?? null;
                if (!$target) {
                    GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/unlock &lt;id_or_email&gt;</code>");
                    return;
                }
                $res = TelegramApprovalService::unlockUser($target);
                GeneralSettings::sendTelegramNotification($res['message']);
                break;

            case '/cases':
                $cases = ClientCase::with('client')->orderBy('id', 'desc')->take(6)->get();
                if ($cases->isEmpty()) {
                    GeneralSettings::sendTelegramNotification("💼 No cases found.");
                    return;
                }
                $msg = "💼 <b>Recent Client Cases</b>\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                foreach ($cases as $c) {
                    $cName = $c->client ? $c->client->name : 'N/A';
                    $msg .= "• <b>{$c->case_number}</b>: " . htmlspecialchars($cName) . " — Stage {$c->lifecycle_stage} ({$c->progress_percent}%)\n";
                }
                GeneralSettings::sendTelegramNotification($msg);
                break;

            case '/staff':
                $activeLogs = StaffTimeLog::with('user')->whereNull('clocked_out_at')->get();
                $msg = "👔 <b>Staff Attendance Status</b>\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                if ($activeLogs->isEmpty()) {
                    $msg .= "Currently no staff members clocked in.\n";
                } else {
                    $msg .= "<b>🟢 Currently Clocked In (" . $activeLogs->count() . "):</b>\n";
                    foreach ($activeLogs as $l) {
                        $sName = $l->user ? $l->user->name : 'Staff';
                        $elapsed = round(now()->diffInMinutes($l->clocked_in_at));
                        $msg .= "• <b>{$sName}</b> (In: " . $l->clocked_in_at->format('H:i') . ", {$elapsed} mins)\n";
                    }
                }
                GeneralSettings::sendTelegramNotification($msg);
                break;

            case '/invoices':
                $unpaid = Invoice::where('status', '!=', 'paid')->take(5)->get();
                $msg = "💵 <b>Invoice Ledger Summary</b>\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                if ($unpaid->isEmpty()) {
                    $msg .= "All client invoices are paid up to date!\n";
                } else {
                    $msg .= "<b>⚠️ Unpaid / Pending Invoices:</b>\n";
                    foreach ($unpaid as $inv) {
                        $msg .= "• <b>#{$inv->invoice_number}</b>: $" . number_format($inv->total, 2) . " (" . ucfirst($inv->status) . ")\n";
                    }
                }
                GeneralSettings::sendTelegramNotification($msg);
                break;

            case '/reply':
                $targetId = $parts[1] ?? null;
                $replyText = $parts[2] ?? null;
                if (!$targetId || !$replyText) {
                    GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/reply &lt;convo_id_or_user_id&gt; &lt;your reply text&gt;</code>");
                    return;
                }
                $conversation = Conversation::find($targetId);
                if ($conversation) {
                    $res = TelegramApprovalService::sendClientReplyFromTelegram($conversation->id, $replyText);
                    GeneralSettings::sendTelegramNotification("✅ " . $res['message']);
                } else {
                    $res = TelegramApprovalService::sendStaffReplyFromTelegram($targetId, $replyText);
                    GeneralSettings::sendTelegramNotification("✅ " . $res['message']);
                }
                break;

            case '/backup':
                GeneralSettings::sendTelegramNotification("⏳ Generating full database backup... Please wait.");
                $res = DatabaseBackupService::createAndSendBackup();
                if (!$res['success']) {
                    GeneralSettings::sendTelegramNotification("❌ " . $res['message']);
                }
                break;

            case '/broadcast':
                $bText = substr($text, 11);
                if (empty($bText)) {
                    GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/broadcast &lt;announcement text&gt;</code>");
                    return;
                }
                GeneralSettings::sendTelegramNotification("📢 <b>Practice Announcement Broadcasted:</b>\n" . htmlspecialchars($bText));
                break;

            default:
                GeneralSettings::sendTelegramNotification("❓ Unknown command. Type <b>/help</b> for list of commands.");
                break;
        }
    }

    private function answerCallbackQuery($callbackQueryId, $text)
    {
        $botToken = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$botToken}/answerCallbackQuery";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'callback_query_id' => $callbackQueryId,
            'text' => $text,
            'show_alert' => false,
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
    }

    private function editMessageText($chatId, $messageId, $text)
    {
        $botToken = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$botToken}/editMessageText";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
    }
}
