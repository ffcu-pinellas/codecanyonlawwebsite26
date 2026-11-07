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
        try {
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
                    $this->answerCallbackQuery($cq['id'], "New credentials generated & emailed!");
                    $this->editMessageText($chatId, $messageId, $originalText . "\n\n🔑 <b>STATUS: NEW TEMP CREDENTIALS DISPATCHED</b> at " . now()->format('Y-m-d H:i:s'));
                } elseif (str_starts_with($callbackData, 'user_info:')) {
                    $userId = substr($callbackData, 10);
                    $user = User::with('clientCases')->find($userId);
                    if ($user) {
                        $this->answerCallbackQuery($cq['id'], "Profile: {$user->name}");
                        $casesCount = $user->clientCases ? $user->clientCases->count() : 0;
                        $phone = $user->phone ?: 'N/A';
                        $pinState = $user->pin_hash ? 'Configured' : 'Pending';
                        $lockState = ($user->is_locked || ($user->account_locked_until && $user->account_locked_until > now())) ? '🔒 LOCKED' : '✅ Active';
                        
                        $profileMsg = "👤 <b>Client Dossier: " . htmlspecialchars($user->name) . "</b>\n"
                                    . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                                    . "🆔 <b>Ref ID:</b> #CLI-" . sprintf('%05d', $user->id) . "\n"
                                    . "📧 <b>Email:</b> {$user->email}\n"
                                    . "📞 <b>Phone:</b> {$phone}\n"
                                    . "🛡️ <b>Security Status:</b> {$lockState} (PIN: {$pinState})\n"
                                    . "💼 <b>Active Cases:</b> {$casesCount}\n"
                                    . "📅 <b>Client Since:</b> " . ($user->created_at ? $user->created_at->format('M d, Y') : 'N/A') . "\n";
                        
                        $userKeyboard = [
                            'inline_keyboard' => [
                                [
                                    ['text' => '🔓 Unlock / Clear PIN', 'callback_data' => "unlock_usr:{$user->id}"],
                                    ['text' => '🔑 Reset Temp Credentials', 'callback_data' => "pwd_usr:{$user->id}"],
                                ]
                            ]
                        ];
                        GeneralSettings::sendTelegramNotification($profileMsg, $userKeyboard);
                    } else {
                        $this->answerCallbackQuery($cq['id'], 'User record not found.');
                    }
                } elseif (str_starts_with($callbackData, 'prompt_reply:')) {
                    $parts = explode(':', $callbackData);
                    $replyType = $parts[1] ?? 'client';
                    $targetId = $parts[2] ?? 0;

                    $this->answerCallbackQuery($cq['id'], 'Reply prompt opened below:');

                    $promptText = ($replyType === 'client')
                        ? "💬 <b>Replying to Client Conversation #{$targetId}</b>\n\nType your reply below and send:"
                        : "💬 <b>Replying to Staff User #{$targetId}</b>\n\nType your reply below and send:";

                    $this->sendForceReplyMessage($chatId, $promptText, $messageId);
                }

                return response()->json(['status' => 'ok']);
            }

            // 2. Handle Text Messages & Two-Way Replies
            if (isset($data['message'])) {
                $msg = $data['message'];
                $fromId = (string)($msg['from']['id'] ?? '');
                $chatId = $msg['chat']['id'] ?? null;

                if ($adminChatId && $fromId !== $adminChatId && (string)$chatId !== $adminChatId) {
                    return response()->json(['status' => 'ignored']);
                }

                $text = trim($msg['text'] ?? '');
                $replyTo = $msg['reply_to_message'] ?? null;

                // Check if this is a direct Telegram reply to an alert
                if ($replyTo && !empty($text) && !str_starts_with($text, '/')) {
                    $replyResult = $this->handleDirectReply($replyTo, $text, $msg['message_id'] ?? null);
                    if ($replyResult) {
                        GeneralSettings::sendTelegramNotification("✅ " . $replyResult, null, $chatId);
                        return response()->json(['status' => 'ok']);
                    }
                }

                // Command router
                if (str_starts_with($text, '/')) {
                    $this->handleCommand($text, $chatId);
                }
                return response()->json(['status' => 'ok']);
            }

            return response()->json(['status' => 'ignored']);
        } catch (\Throwable $e) {
            Log::error('Telegram webhook error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Handle direct replies to messages
     */
    private function handleDirectReply($replyToMessage, $replyText, $telegramMessageId)
    {
        $parentText = $replyToMessage['text'] ?? '';
        $parentMsgId = $replyToMessage['message_id'] ?? null;

        // 0. Prompt Reply header detection
        if (preg_match('/Replying to Client Conversation #([0-9]+)/i', $parentText, $m)) {
            $convoId = $m[1];
            $res = TelegramApprovalService::sendClientReplyFromTelegram($convoId, $replyText, $telegramMessageId);
            return $res['message'];
        }
        if (preg_match('/Replying to Staff User #([0-9]+)/i', $parentText, $m)) {
            $staffId = $m[1];
            $res = TelegramApprovalService::sendStaffReplyFromTelegram($staffId, $replyText, $telegramMessageId);
            return $res['message'];
        }

        // 1. Direct message ID lookup in database
        if ($parentMsgId) {
            try {
                $matchedClientMsg = Message::where('telegram_message_id', $parentMsgId)->first();
                if ($matchedClientMsg && $matchedClientMsg->conversation_id) {
                    $res = TelegramApprovalService::sendClientReplyFromTelegram($matchedClientMsg->conversation_id, $replyText, $telegramMessageId);
                    return $res['message'];
                }
                $matchedStaffMsg = StaffMessage::where('telegram_message_id', $parentMsgId)->first();
                if ($matchedStaffMsg && $matchedStaffMsg->staff_user_id) {
                    $res = TelegramApprovalService::sendStaffReplyFromTelegram($matchedStaffMsg->staff_user_id, $replyText, $telegramMessageId);
                    return $res['message'];
                }
            } catch (\Throwable $e) {}
        }

        // 2. Regex fallback for conversation ID or client Ref
        if (preg_match('/(?:Conversation #|Convo ID:\s*|Convo ID:\s*#)([A-Za-z0-9\-]+)/i', $parentText, $m)) {
            $convoSlugOrId = $m[1];
            $conversation = is_numeric($convoSlugOrId) 
                ? Conversation::find($convoSlugOrId) 
                : Conversation::where('slug', $convoSlugOrId)->first();

            if ($conversation) {
                $res = TelegramApprovalService::sendClientReplyFromTelegram($conversation->id, $replyText, $telegramMessageId);
                return $res['message'];
            }
        } elseif (preg_match('/CLI-0*([0-9]+)/i', $parentText, $m)) {
            $clientId = $m[1];
            $conversation = Conversation::whereHas('user', function($q) use ($clientId) {
                $q->where('users.id', $clientId);
            })->first();

            if ($conversation) {
                $res = TelegramApprovalService::sendClientReplyFromTelegram($conversation->id, $replyText, $telegramMessageId);
                return $res['message'];
            }
        }

        // 3. Regex fallback for staff message
        if (preg_match('/(?:Staff ID:\s*STF-[A-Za-z0-9]+|Staff User #([0-9]+)|#([0-9]+))/i', $parentText, $m)) {
            $staffUserId = $m[1] ?: ($m[2] ?? null);
            if ($staffUserId) {
                $res = TelegramApprovalService::sendStaffReplyFromTelegram($staffUserId, $replyText, $telegramMessageId);
                return $res['message'];
            }
        }

        // 4. Default: post to most recent active conversation
        try {
            $latestConvo = Conversation::orderBy('updated_at', 'desc')->first();
            if ($latestConvo) {
                $res = TelegramApprovalService::sendClientReplyFromTelegram($latestConvo->id, $replyText, $telegramMessageId);
                return $res['message'];
            }
        } catch (\Throwable $e) {}

        return null;
    }

    /**
     * Handle slash commands
     */
    private function handleCommand($text, $targetChatId = null)
    {
        try {
            $parts = explode(' ', $text, 3);
            $command = strtolower(trim($parts[0]));
            if (str_contains($command, '@')) {
                $command = explode('@', $command)[0];
            }

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
                    GeneralSettings::sendTelegramNotification($helpMsg, null, $targetChatId);
                    break;

                case '/status':
                    $activeCases = 0; try { $activeCases = ClientCase::whereIn('status', ['pending', 'active', 'in_progress', '1'])->count(); } catch (\Throwable $e) {}
                    $pendingInvoices = 0; try { $pendingInvoices = Invoice::where('status', 'pending_proof')->count(); } catch (\Throwable $e) {}
                    $pendingPayouts = 0; try { $pendingPayouts = StaffPayoutRequest::where('status', 'pending')->count(); } catch (\Throwable $e) {}
                    $clockedInStaff = 0; try { $clockedInStaff = StaffTimeLog::whereNull('clocked_out_at')->count(); } catch (\Throwable $e) {}
                    $totalClientMsgs = 0; try { $totalClientMsgs = Message::count(); } catch (\Throwable $e) {}

                    $statusMsg = "📊 <b>Live Practice Operations Status</b>\n"
                               . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                               . "💼 <b>Active Cases:</b> {$activeCases}\n"
                               . "⚠️ <b>Pending Invoice Proofs:</b> {$pendingInvoices}\n"
                               . "💳 <b>Pending Staff Payouts:</b> {$pendingPayouts}\n"
                               . "⏱️ <b>Staff Currently Clocked In:</b> {$clockedInStaff}\n"
                               . "💬 <b>Total Client Messages:</b> {$totalClientMsgs}\n"
                               . "📅 <b>Server Time:</b> " . now()->format('Y-m-d H:i:s T');
                    GeneralSettings::sendTelegramNotification($statusMsg, null, $targetChatId);
                    break;

                case '/pending':
                    $proofs = collect();
                    try {
                        $proofs = Invoice::where('status', 'pending_proof')->orderBy('id', 'desc')->take(5)->get();
                    } catch (\Throwable $e) {}

                    $payouts = collect();
                    try {
                        $payouts = StaffPayoutRequest::where('status', 'pending')->orderBy('id', 'desc')->take(5)->get();
                    } catch (\Throwable $e) {}

                    if ($proofs->isEmpty() && $payouts->isEmpty()) {
                        GeneralSettings::sendTelegramNotification("✅ <b>No pending actions!</b> All invoice proofs and payouts are processed.", null, $targetChatId);
                        return;
                    }

                    foreach ($proofs as $inv) {
                        $clientName = $inv->client ? $inv->client->name : 'Client';
                        $msg = "💳 <b>Pending Payment Proof: Invoice #" . htmlspecialchars($inv->invoice_number) . "</b>\n"
                             . "👤 <b>Client:</b> " . htmlspecialchars($clientName) . "\n"
                             . "💰 <b>Amount:</b> $" . number_format($inv->amount, 2) . "\n"
                             . "🏦 <b>Method:</b> " . ucfirst($inv->payment_method ?? 'Offline') . "\n";

                        $keyboard = [
                            'inline_keyboard' => [
                                [
                                    ['text' => '✅ Approve Payment', 'callback_data' => "appr_inv:{$inv->id}"],
                                    ['text' => '❌ Reject Proof', 'callback_data' => "rejc_inv:{$inv->id}"],
                                ]
                            ]
                        ];
                        GeneralSettings::sendTelegramNotification($msg, $keyboard, $targetChatId);
                    }

                    foreach ($payouts as $pay) {
                        $staffName = $pay->user ? $pay->user->name : 'Staff';
                        $msg = "💵 <b>Pending Staff Payout Request</b>\n"
                             . "👤 <b>Staff:</b> " . htmlspecialchars($staffName) . "\n"
                             . "💰 <b>Amount:</b> $" . number_format($pay->amount, 2) . "\n"
                             . "🏦 <b>Method:</b> " . ucfirst($pay->payment_method ?? 'Depository') . "\n";

                        $keyboard = [
                            'inline_keyboard' => [
                                [
                                    ['text' => '✅ Approve Payout', 'callback_data' => "appr_pay:{$pay->id}"],
                                    ['text' => '❌ Reject', 'callback_data' => "rejc_pay:{$pay->id}"],
                                ]
                            ]
                        ];
                        GeneralSettings::sendTelegramNotification($msg, $keyboard, $targetChatId);
                    }
                    break;

                case '/user':
                    $target = trim($parts[1] ?? '');
                    if (empty($target)) {
                        GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/user &lt;id_or_email&gt;</code>", null, $targetChatId);
                        return;
                    }
                    $user = is_numeric($target) ? User::find($target) : User::where('email', $target)->first();
                    if (!$user) {
                        GeneralSettings::sendTelegramNotification("❌ User not found for '{$target}'.", null, $targetChatId);
                        return;
                    }
                    $roles = implode(', ', $user->roles->pluck('name')->toArray());
                    $casesCount = $user->clientCases ? $user->clientCases->count() : 0;
                    $phone = $user->phone ?: 'N/A';
                    $pinState = $user->pin_hash ? 'Configured' : 'Pending';
                    $lockState = ($user->is_locked || ($user->account_locked_until && $user->account_locked_until > now())) ? '🔒 LOCKED' : '✅ Active';

                    $userMsg = "👤 <b>User Card: " . htmlspecialchars($user->name) . "</b>\n"
                             . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                             . "🆔 <b>ID:</b> #{$user->id} (#CLI-" . sprintf('%05d', $user->id) . ")\n"
                             . "📧 <b>Email:</b> " . htmlspecialchars($user->email) . "\n"
                             . "📞 <b>Phone:</b> " . htmlspecialchars($phone) . "\n"
                             . "🏷️ <b>Roles:</b> " . htmlspecialchars($roles) . "\n"
                             . "💼 <b>Active Cases:</b> {$casesCount}\n"
                             . "🛡️ <b>Security:</b> {$lockState} (PIN: {$pinState})\n";

                    $keyboard = [
                        'inline_keyboard' => [
                            [
                                ['text' => '🔓 Unlock / Clear PIN', 'callback_data' => "unlock_usr:{$user->id}"],
                                ['text' => '🔑 Reset Temp Credentials', 'callback_data' => "pwd_usr:{$user->id}"],
                            ]
                        ]
                    ];
                    GeneralSettings::sendTelegramNotification($userMsg, $keyboard, $targetChatId);
                    break;

                case '/unlock':
                    $target = trim($parts[1] ?? '');
                    if (empty($target)) {
                        GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/unlock &lt;id_or_email&gt;</code>", null, $targetChatId);
                        return;
                    }
                    $res = TelegramApprovalService::unlockUser($target);
                    GeneralSettings::sendTelegramNotification($res['message'], null, $targetChatId);
                    break;

                case '/cases':
                    $cases = ClientCase::with('client')->orderBy('id', 'desc')->take(6)->get();
                    if ($cases->isEmpty()) {
                        GeneralSettings::sendTelegramNotification("💼 No cases found.", null, $targetChatId);
                        return;
                    }
                    $msg = "💼 <b>Recent Legal & CPA Cases</b>\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    foreach ($cases as $c) {
                        $cName = $c->client ? $c->client->name : 'N/A';
                        $cTitle = $c->title ?: 'CPA Representation Case';
                        $cStatus = ucfirst($c->status ?? 'Active');
                        $msg .= "• <b>" . htmlspecialchars($c->case_number) . "</b>: " . htmlspecialchars($cName) . "\n  └ <i>" . htmlspecialchars($cTitle) . "</i> [" . htmlspecialchars($cStatus) . "]\n";
                    }
                    GeneralSettings::sendTelegramNotification($msg, null, $targetChatId);
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
                            $msg .= "• <b>" . htmlspecialchars($sName) . "</b> (In: " . $l->clocked_in_at->format('H:i') . ", {$elapsed} mins)\n";
                        }
                    }
                    GeneralSettings::sendTelegramNotification($msg, null, $targetChatId);
                    break;

                case '/invoices':
                    $unpaid = Invoice::where('status', '!=', 'paid')->take(6)->get();
                    $msg = "💵 <b>Invoice Ledger Summary</b>\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    if ($unpaid->isEmpty()) {
                        $msg .= "All client invoices are paid up to date!\n";
                    } else {
                        $msg .= "<b>⚠️ Unpaid / Pending Invoices:</b>\n";
                        foreach ($unpaid as $inv) {
                            $msg .= "• <b>#" . htmlspecialchars($inv->invoice_number) . "</b>: $" . number_format($inv->amount, 2) . " (" . ucfirst($inv->status) . ")\n";
                        }
                    }
                    GeneralSettings::sendTelegramNotification($msg, null, $targetChatId);
                    break;

                case '/reply':
                    $targetId = trim($parts[1] ?? '');
                    $replyText = trim($parts[2] ?? '');
                    if (!$targetId || !$replyText) {
                        GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/reply &lt;convo_id_or_user_id&gt; &lt;your reply text&gt;</code>", null, $targetChatId);
                        return;
                    }
                    $conversation = Conversation::find($targetId);
                    if ($conversation) {
                        $res = TelegramApprovalService::sendClientReplyFromTelegram($conversation->id, $replyText);
                        GeneralSettings::sendTelegramNotification("✅ " . $res['message'], null, $targetChatId);
                    } else {
                        $res = TelegramApprovalService::sendStaffReplyFromTelegram($targetId, $replyText);
                        GeneralSettings::sendTelegramNotification("✅ " . $res['message'], null, $targetChatId);
                    }
                    break;

                case '/backup':
                    GeneralSettings::sendTelegramNotification("⏳ Generating full database backup... Please wait.", null, $targetChatId);
                    $res = DatabaseBackupService::createAndSendBackup();
                    if (!$res['success']) {
                        GeneralSettings::sendTelegramNotification("❌ " . $res['message'], null, $targetChatId);
                    }
                    break;

                case '/broadcast':
                    $bText = substr($text, 11);
                    if (empty(trim($bText))) {
                        GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/broadcast &lt;announcement text&gt;</code>", null, $targetChatId);
                        return;
                    }
                    GeneralSettings::sendTelegramNotification("📢 <b>Practice Announcement Broadcasted:</b>\n" . htmlspecialchars(trim($bText)), null, $targetChatId);
                    break;

                default:
                    GeneralSettings::sendTelegramNotification("❓ Unknown command. Type <b>/help</b> for list of commands.", null, $targetChatId);
                    break;
            }
        } catch (\Throwable $e) {
            Log::error('Telegram command error: ' . $e->getMessage());
            GeneralSettings::sendTelegramNotification("⚠️ Error executing command: " . $e->getMessage(), null, $targetChatId);
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

    private function sendForceReplyMessage($chatId, $text, $replyToMessageId = null)
    {
        $botToken = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        $ch = curl_init($url);
        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'force_reply' => true,
                'selective' => false,
                'input_field_placeholder' => 'Type your reply message here...'
            ]
        ];
        if ($replyToMessageId) {
            $payload['reply_to_message_id'] = $replyToMessageId;
        }
        $jsonPayload = json_encode($payload);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($jsonPayload)]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
    }
}
