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
        if (str_starts_with($webhookUrl, 'http://')) {
            $webhookUrl = 'https://' . substr($webhookUrl, 7);
        }

        $telegramApi = "https://api.telegram.org/bot{$botToken}/setWebhook?url=" . urlencode($webhookUrl);
        $res = @file_get_contents($telegramApi);

        // Fetch live webhook info
        $infoApi = "https://api.telegram.org/bot{$botToken}/getWebhookInfo";
        $infoRes = @file_get_contents($infoApi);

        return response()->json([
            'setup_status' => json_decode($res, true),
            'webhook_info' => json_decode($infoRes, true),
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
                if ($adminChatId) {
                    $normAdmin = ltrim($adminChatId, '-100');
                    $normFrom = ltrim($fromId, '-100');
                    $isAuth = ($fromId === $adminChatId) || ($normFrom === $normAdmin);
                    if (!$isAuth) {
                        $this->answerCallbackQuery($cq['id'], '⚠️ Unauthorized access.');
                        return response()->json(['status' => 'ignored']);
                    }
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
                } elseif (str_starts_with($callbackData, 'appr_tsk:')) {
                    $taskId = substr($callbackData, 9);
                    $res = TelegramApprovalService::approveStaffTask($taskId, 'Telegram Admin');
                    $this->answerCallbackQuery($cq['id'], $res['message']);
                    if ($res['success']) {
                        $this->editMessageText($chatId, $messageId, $originalText . "\n\n✅ <b>TASK APPROVED & MARKED COMPLETED</b> at " . now()->format('Y-m-d H:i:s'));
                    }
                } elseif (str_starts_with($callbackData, 'rejc_tsk:')) {
                    $taskId = substr($callbackData, 9);
                    $res = TelegramApprovalService::rejectStaffTask($taskId, 'Returned by management for revision.', 'Telegram Admin');
                    $this->answerCallbackQuery($cq['id'], $res['message']);
                    if ($res['success']) {
                        $this->editMessageText($chatId, $messageId, $originalText . "\n\n⚠️ <b>TASK RETURNED FOR REVISIONS</b> at " . now()->format('Y-m-d H:i:s'));
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
                        $lockState = ($user->failed_pin_attempts ?? 0) >= 5 ? '🔒 LOCKED' : '✅ Active';
                        
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

                if ($adminChatId) {
                    $normAdmin = ltrim($adminChatId, '-100');
                    $normFrom = ltrim($fromId, '-100');
                    $normChat = ltrim((string)$chatId, '-100');
                    $isAuth = ($fromId === $adminChatId) 
                           || ((string)$chatId === $adminChatId)
                           || ($normFrom === $normAdmin)
                           || ($normChat === $normAdmin);

                    if (!$isAuth) {
                        Log::warning("Telegram webhook ignored unauthorized: fromId={$fromId}, chatId={$chatId}, adminChatId={$adminChatId}");
                        return response()->json(['status' => 'ignored']);
                    }
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
                             . "🔒 <b>/setpin &lt;4-digit-pin&gt;</b> — Set or change Admin 2FA Gate PIN\n"
                             . "⏳ <b>/pending</b> — Review pending invoice proofs & payout requests\n"
                             . "📋 <b>/tasks</b> — Review submitted staff tasks with 1-click approval\n"
                             . "✅ <b>/approvetask &lt;id&gt;</b> — Mark staff task completed\n"
                             . "⚠️ <b>/rejecttask &lt;id&gt; &lt;notes&gt;</b> — Return task for revision\n"
                             . "💳 <b>/payouts</b> — Review and approve pending staff payouts\n"
                             . "💰 <b>/staffbalance &lt;id/email&gt;</b> — Financial ledger & claimable balance\n"
                             . "👤 <b>/user &lt;id/email&gt;</b> — Client profile, case state & quick actions\n"
                             . "🔓 <b>/unlock &lt;id/email&gt;</b> — Reset PIN locks & relax 2FA\n"
                             . "📁 <b>/cases</b> — Recent active cases and progress stages\n"
                             . "👔 <b>/staff</b> or <b>/clocked</b> — Currently clocked-in staff & hours today\n"
                             . "📄 <b>/documents</b> — Pending documents awaiting client execution\n"
                             . "💵 <b>/invoices</b> — Unpaid balances & recently cleared payments\n"
                             . "💬 <b>/reply &lt;id&gt; &lt;text&gt;</b> — Direct reply to client or staff chat\n"
                             . "📢 <b>/broadcast &lt;text&gt;</b> — Send announcement\n"
                             . "📦 <b>/backup</b> — Dispatch instant encrypted DB backup (.sql.gz)\n";
                    GeneralSettings::sendTelegramNotification($helpMsg, null, $targetChatId);
                    break;

                case '/status':
                    try {
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
                                   . "📅 <b>Server Time:</b> " . now()->format('Y-m-d H:i:s');
                        GeneralSettings::sendTelegramNotification($statusMsg, null, $targetChatId);
                    } catch (\Throwable $e) {
                        Log::error('Telegram /status error: ' . $e->getMessage());
                        GeneralSettings::sendTelegramNotification("⚠️ Status error: " . $e->getMessage(), null, $targetChatId);
                    }
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
                    try {
                        $cases = ClientCase::with('client')->orderBy('id', 'desc')->take(6)->get();
                        if ($cases->isEmpty()) {
                            GeneralSettings::sendTelegramNotification("💼 No cases found in practice system.", null, $targetChatId);
                            return;
                        }
                        $msg = "💼 <b>Recent Legal & CPA Cases</b>\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                        foreach ($cases as $c) {
                            $cName = $c->client ? $c->client->name : 'N/A';
                            $cTitle = $c->title ?: 'CPA Representation Case';
                            $cStatus = ucfirst($c->status ?? 'Active');
                            $cNum = $c->case_number ?: ('#' . $c->id);
                            $msg .= "• <b>" . htmlspecialchars($cNum) . "</b>: " . htmlspecialchars($cName) . "\n  └ <i>" . htmlspecialchars($cTitle) . "</i> [" . htmlspecialchars($cStatus) . "]\n";
                        }
                        GeneralSettings::sendTelegramNotification($msg, null, $targetChatId);
                    } catch (\Throwable $e) {
                        Log::error('Telegram /cases error: ' . $e->getMessage());
                        GeneralSettings::sendTelegramNotification("⚠️ Cases error: " . $e->getMessage(), null, $targetChatId);
                    }
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

                case '/pin':
                case '/setpin':
                    $arg1 = trim($parts[1] ?? '');
                    $arg2 = trim($parts[2] ?? '');

                    if (!empty($arg2) && preg_match('/^[0-9]{4}$/', $arg2)) {
                        // /setpin <user_id_or_email> <4-digit-pin>
                        $targetUser = is_numeric($arg1) ? User::find($arg1) : User::where('email', $arg1)->first();
                        if ($targetUser) {
                            $targetUser->pin_hash = \Illuminate\Support\Facades\Hash::make($arg2);
                            $targetUser->two_factor_enabled = 1;
                            $targetUser->otp_code = null;
                            $targetUser->otp_expires_at = null;
                            if (isset($targetUser->failed_pin_attempts)) {
                                $targetUser->failed_pin_attempts = 0;
                            }
                            $targetUser->save();

                            $msg = "✅ <b>Security PIN Configured!</b>\n"
                                 . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                                 . "👤 <b>User:</b> " . htmlspecialchars($targetUser->name) . " ({$targetUser->email})\n"
                                 . "🔒 <b>New PIN:</b> <code>{$arg2}</code>\n"
                                 . "🛡️ <b>2FA Gate:</b> Active (Security Gate unlocked)\n\n"
                                 . "User can now authenticate immediately with this 4-digit PIN.";
                            GeneralSettings::sendTelegramNotification($msg, null, $targetChatId);
                            break;
                        }
                    }

                    if (preg_match('/^[0-9]{4}$/', $arg1)) {
                        // /setpin <4-digit-pin> (Sets PIN for all admin/attorney/practice director accounts)
                        $adminUsers = User::whereHas('roles', function($q) { $q->whereIn('name', ['admin', 'attorney']); })
                            ->orWhere('id', 1)
                            ->orWhere('email', 'like', '%admin%')
                            ->orWhere('name', 'like', '%Allen%')
                            ->orWhere('name', 'like', '%Gerald%')
                            ->orWhere('email', 'like', '%yourcpaexpert%')
                            ->get();

                        if ($adminUsers->isNotEmpty()) {
                            $updatedNames = [];
                            foreach ($adminUsers as $adminUser) {
                                $adminUser->pin_hash = \Illuminate\Support\Facades\Hash::make($arg1);
                                $adminUser->two_factor_enabled = 1;
                                $adminUser->otp_code = null;
                                $adminUser->otp_expires_at = null;
                                if (isset($adminUser->failed_pin_attempts)) {
                                    $adminUser->failed_pin_attempts = 0;
                                }
                                $adminUser->save();
                                $updatedNames[] = htmlspecialchars($adminUser->name) . " ({$adminUser->email})";
                            }

                            $msg = "✅ <b>Admin 2FA Security PIN Updated!</b>\n"
                                 . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                                 . "👤 <b>Updated:</b> " . implode(', ', $updatedNames) . "\n"
                                 . "🔒 <b>New PIN:</b> <code>{$arg1}</code>\n"
                                 . "🛡️ <b>2FA Security Gate:</b> Active & Persistent\n\n"
                                 . "You can now enter this 4-digit PIN to unlock sessions and authenticate across all portals immediately.";
                            GeneralSettings::sendTelegramNotification($msg, null, $targetChatId);
                        } else {
                            GeneralSettings::sendTelegramNotification("⚠️ No admin account found in database to update.", null, $targetChatId);
                        }
                        break;
                    }

                    GeneralSettings::sendTelegramNotification("⚠️ <b>Invalid PIN:</b> Please provide a 4-digit numeric code.\n\n<b>Usage:</b>\n• <code>/setpin 1234</code> (Sets your Admin PIN)\n• <code>/setpin &lt;id/email&gt; 1234</code> (Sets PIN for a user)", null, $targetChatId);
                    break;

                case '/tasks':
                    $tasks = \App\Models\StaffTask::with('user')->whereIn('status', ['review', 'pending'])->orderBy('id', 'desc')->take(6)->get();
                    if ($tasks->isEmpty()) {
                        GeneralSettings::sendTelegramNotification("📋 <b>No tasks awaiting review.</b> All operational tasks are processed.", null, $targetChatId);
                        return;
                    }
                    foreach ($tasks as $tsk) {
                        $sName = $tsk->user ? $tsk->user->name : 'Staff';
                        $tTitle = htmlspecialchars($tsk->title);
                        $tStatus = strtoupper($tsk->status);
                        $tskMsg = "📋 <b>Task #{$tsk->id}: {$tTitle}</b>\n"
                                . "👤 <b>Assigned:</b> {$sName}\n"
                                . "📌 <b>Status:</b> {$tStatus}\n"
                                . "📅 <b>Due Date:</b> " . ($tsk->due_date ? date('M d, Y', strtotime($tsk->due_date)) : 'N/A') . "\n";
                        $kb = [
                            'inline_keyboard' => [
                                [
                                    ['text' => '✅ Approve Task', 'callback_data' => "appr_tsk:{$tsk->id}"],
                                    ['text' => '⚠️ Revise / Reject', 'callback_data' => "rejc_tsk:{$tsk->id}"],
                                ]
                            ]
                        ];
                        GeneralSettings::sendTelegramNotification($tskMsg, $kb, $targetChatId);
                    }
                    break;

                case '/approvetask':
                    $tId = trim($parts[1] ?? '');
                    if (!$tId) {
                        GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/approvetask &lt;task_id&gt;</code>", null, $targetChatId);
                        return;
                    }
                    $res = TelegramApprovalService::approveStaffTask($tId, 'Telegram Admin');
                    GeneralSettings::sendTelegramNotification(($res['success'] ? '✅ ' : '⚠️ ') . $res['message'], null, $targetChatId);
                    break;

                case '/rejecttask':
                    $tId = trim($parts[1] ?? '');
                    $notes = trim($parts[2] ?? 'Task returned for quality revisions.');
                    if (!$tId) {
                        GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/rejecttask &lt;task_id&gt; [notes]</code>", null, $targetChatId);
                        return;
                    }
                    $res = TelegramApprovalService::rejectStaffTask($tId, $notes, 'Telegram Admin');
                    GeneralSettings::sendTelegramNotification(($res['success'] ? '✅ ' : '⚠️ ') . $res['message'], null, $targetChatId);
                    break;

                case '/payouts':
                    $payouts = StaffPayoutRequest::with('user')->where('status', 'pending')->orderBy('id', 'desc')->take(6)->get();
                    if ($payouts->isEmpty()) {
                        GeneralSettings::sendTelegramNotification("💳 <b>No pending staff payout requests.</b>", null, $targetChatId);
                        return;
                    }
                    foreach ($payouts as $pay) {
                        $staffName = $pay->user ? $pay->user->name : 'Staff';
                        $pMsg = "💳 <b>Staff Payout Request #{$pay->id}</b>\n"
                              . "👤 <b>Staff:</b> " . htmlspecialchars($staffName) . "\n"
                              . "💰 <b>Amount:</b> $" . number_format($pay->amount, 2) . "\n"
                              . "🏦 <b>Method:</b> " . ucfirst($pay->payment_method ?? 'Depository') . "\n";
                        $kb = [
                            'inline_keyboard' => [
                                [
                                    ['text' => '✅ Approve Payout', 'callback_data' => "appr_pay:{$pay->id}"],
                                    ['text' => '❌ Reject', 'callback_data' => "rejc_pay:{$pay->id}"],
                                ]
                            ]
                        ];
                        GeneralSettings::sendTelegramNotification($pMsg, $kb, $targetChatId);
                    }
                    break;

                case '/approvepayout':
                    $pId = trim($parts[1] ?? '');
                    if (!$pId) {
                        GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/approvepayout &lt;payout_id&gt;</code>", null, $targetChatId);
                        return;
                    }
                    $res = TelegramApprovalService::approveStaffPayout($pId, 'Telegram Admin');
                    GeneralSettings::sendTelegramNotification(($res['success'] ? '✅ ' : '⚠️ ') . $res['message'], null, $targetChatId);
                    break;

                case '/rejectpayout':
                    $pId = trim($parts[1] ?? '');
                    if (!$pId) {
                        GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/rejectpayout &lt;payout_id&gt;</code>", null, $targetChatId);
                        return;
                    }
                    $res = TelegramApprovalService::rejectStaffPayout($pId, 'Declined by Admin via Telegram.', 'Telegram Admin');
                    GeneralSettings::sendTelegramNotification(($res['success'] ? '✅ ' : '⚠️ ') . $res['message'], null, $targetChatId);
                    break;

                case '/clocked':
                    $activeLogs = StaffTimeLog::with('user')->whereNull('clocked_out_at')->get();
                    $msg = "⏱️ <b>Active Staff Shifts</b>\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    if ($activeLogs->isEmpty()) {
                        $msg .= "Currently no staff members clocked in.\n";
                    } else {
                        $msg .= "<b>🟢 Clocked In (" . $activeLogs->count() . "):</b>\n";
                        foreach ($activeLogs as $l) {
                            $sName = $l->user ? $l->user->name : 'Staff';
                            $elapsed = round(now()->diffInMinutes($l->clocked_in_at));
                            $hours = floor($elapsed / 60);
                            $mins = $elapsed % 60;
                            $msg .= "• <b>" . htmlspecialchars($sName) . "</b>: In at " . $l->clocked_in_at->format('H:i') . " ({$hours}h {$mins}m active)\n";
                        }
                    }
                    GeneralSettings::sendTelegramNotification($msg, null, $targetChatId);
                    break;

                case '/staffbalance':
                    $target = trim($parts[1] ?? '');
                    if (!$target) {
                        GeneralSettings::sendTelegramNotification("⚠️ Usage: <code>/staffbalance &lt;user_id_or_email&gt;</code>", null, $targetChatId);
                        return;
                    }
                    $sUser = is_numeric($target) ? User::find($target) : User::where('email', $target)->first();
                    if (!$sUser) {
                        GeneralSettings::sendTelegramNotification("❌ Staff member not found for '{$target}'.", null, $targetChatId);
                        return;
                    }
                    $hourlyRate = $sUser->staffDetail ? (float)($sUser->staffDetail->hourly_rate ?? 0) : 0;
                    $timeLogs = StaffTimeLog::where('user_id', $sUser->id)->whereNotNull('clocked_out_at')->get();
                    $totalMinutes = $timeLogs->sum('total_minutes');
                    $totalHours = round($totalMinutes / 60, 2);
                    $totalEarned = round($totalHours * $hourlyRate, 2);

                    $entries = StaffLedgerEntry::where('user_id', $sUser->id)->get();
                    $reimbursements = $entries->where('type', 'reimbursement')->where('status', 'approved')->sum('amount');
                    $bonuses = $entries->where('type', 'bonus')->where('status', 'approved')->sum('amount');
                    $debts = $entries->where('type', 'debt')->where('status', 'approved')->sum('amount');
                    $netLedger = ($reimbursements + $bonuses) - $debts;

                    $totalGross = $totalEarned + $netLedger;
                    $totalPaidOut = StaffPayoutRequest::where('user_id', $sUser->id)->whereIn('status', ['approved', 'paid'])->sum('amount');
                    $pendingPayouts = StaffPayoutRequest::where('user_id', $sUser->id)->where('status', 'pending')->sum('amount');
                    $availableBalance = max(0, $totalGross - $totalPaidOut - $pendingPayouts);

                    $bMsg = "💰 <b>Synchronized Staff Financial Ledger</b>\n"
                          . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                          . "👤 <b>Staff:</b> " . htmlspecialchars($sUser->name) . " ({$sUser->email})\n"
                          . "⏱️ <b>Hourly Rate:</b> $" . number_format($hourlyRate, 2) . "/hr\n"
                          . "🕒 <b>Hours Worked:</b> {$totalHours} hrs\n"
                          . "💵 <b>Accumulated Wages:</b> $" . number_format($totalEarned, 2) . "\n"
                          . "📊 <b>Net Adjustments:</b> " . ($netLedger >= 0 ? '+' : '-') . "$" . number_format(abs($netLedger), 2) . "\n"
                          . "💳 <b>Disbursed / Paid:</b> $" . number_format($totalPaidOut, 2) . "\n"
                          . "⏳ <b>Pending Holds:</b> $" . number_format($pendingPayouts, 2) . "\n"
                          . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                          . "💎 <b>Available Claimable Balance:</b> $" . number_format($availableBalance, 2) . "\n";
                    GeneralSettings::sendTelegramNotification($bMsg, null, $targetChatId);
                    break;

                case '/documents':
                    $docs = \App\Models\CaseDocument::with(['case', 'case.client'])->where('requires_signature', true)->where('is_signed', false)->take(6)->get();
                    if ($docs->isEmpty()) {
                        GeneralSettings::sendTelegramNotification("📄 <b>No pending unsigned documents.</b> All client case documents are executed.", null, $targetChatId);
                        return;
                    }
                    $msg = "📄 <b>Case Documents Awaiting Execution (" . $docs->count() . ")</b>\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    foreach ($docs as $d) {
                        $cNum = $d->case ? ($d->case->case_number ?: '#' . $d->case->id) : 'N/A';
                        $clName = ($d->case && $d->case->client) ? $d->case->client->name : 'Client';
                        $msg .= "• <b>" . htmlspecialchars($d->document_title) . "</b>\n  └ Case: {$cNum} ({$clName}) [Awaiting Sign]\n";
                    }
                    GeneralSettings::sendTelegramNotification($msg, null, $targetChatId);
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
