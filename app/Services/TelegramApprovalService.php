<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\StaffPayoutRequest;
use App\Models\StaffLedgerEntry;
use App\Models\StaffMessage;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use App\Models\SystemAuditLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class TelegramApprovalService
{
    /**
     * Approve client invoice payment proof
     */
    public static function approveInvoiceProof($invoiceId, $approvedBy = 'Telegram Admin')
    {
        return DB::transaction(function () use ($invoiceId, $approvedBy) {
            $invoice = Invoice::lockForUpdate()->find($invoiceId);
            if (!$invoice) {
                return ['success' => false, 'message' => 'Invoice not found.'];
            }

            if ($invoice->status === 'paid') {
                return ['success' => false, 'message' => "Invoice #{$invoice->invoice_number} is already marked as paid."];
            }

            $invoice->status = 'paid';
            $invoice->paid_at = now();
            $invoice->payment_proof_status = 'approved';
            $invoice->admin_feedback = "Approved via {$approvedBy} on " . now()->format('Y-m-d H:i:s');
            $invoice->save();

            // If related case exists, reflect retainer deposit or settlement
            if ($invoice->case_id && $invoice->case) {
                $case = $invoice->case;
                if ($case->settlement) {
                    $case->settlement->increment('gross_amount', $invoice->total);
                }
            }

            // Log action
            SystemAuditLog::logAction(
                'PAYMENT_PROOF_APPROVED',
                "Invoice #{$invoice->invoice_number} payment proof approved via {$approvedBy}. Amount: \${$invoice->total}",
                $invoice->user_id,
                'admin'
            );

            // Send branded receipt email to client
            try {
                if ($invoice->user && $invoice->user->email) {
                    $client = $invoice->user;
                    $subject = "Payment Receipt: Invoice #{$invoice->invoice_number} (Confidential)";
                    $body = "
                    <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                        <div style='background: #0f172a; color: #ffffff; padding: 24px; text-align: center; border-bottom: 3px solid #10b981;'>
                            <h2 style='margin: 0; color: #ffffff; font-size: 20px;'>" . config('app.name', 'Your CPA Expert') . "</h2>
                            <p style='margin: 4px 0 0 0; color: #94a3b8; font-size: 13px;'>Official Payment Confirmation Receipt</p>
                        </div>
                        <div style='padding: 24px;'>
                            <p>Dear <strong>" . e($client->name) . "</strong>,</p>
                            <p>We are pleased to confirm that your payment for <strong>Invoice #{$invoice->invoice_number}</strong> has been verified and marked as <strong>PAID</strong>.</p>
                            <div style='background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #10b981; border-radius: 6px; padding: 16px; margin: 20px 0;'>
                                <p style='margin: 4px 0;'><strong>Invoice #:</strong> {$invoice->invoice_number}</p>
                                <p style='margin: 4px 0;'><strong>Amount Cleared:</strong> $" . number_format($invoice->total, 2) . "</p>
                                <p style='margin: 4px 0;'><strong>Payment Method:</strong> " . ucfirst($invoice->payment_method ?? 'Depository') . "</p>
                                <p style='margin: 4px 0;'><strong>Date Cleared:</strong> " . now()->format('M d, Y H:i:s T') . "</p>
                            </div>
                            <p>Your case files have been updated accordingly. Thank you for your continued trust in our practice.</p>
                        </div>
                    </div>";

                    Mail::html($body, function ($m) use ($client, $subject) {
                        $m->to($client->email, $client->name)->subject($subject);
                    });
                }
            } catch (\Throwable $e) {}

            return [
                'success' => true,
                'message' => "Invoice #{$invoice->invoice_number} (\${$invoice->total}) successfully approved and receipt sent.",
                'invoice' => $invoice,
            ];
        });
    }

    /**
     * Reject client invoice payment proof
     */
    public static function rejectInvoiceProof($invoiceId, $reason = 'Unable to verify payment proof.', $rejectedBy = 'Telegram Admin')
    {
        return DB::transaction(function () use ($invoiceId, $reason, $rejectedBy) {
            $invoice = Invoice::lockForUpdate()->find($invoiceId);
            if (!$invoice) {
                return ['success' => false, 'message' => 'Invoice not found.'];
            }

            $invoice->payment_proof_status = 'rejected';
            $invoice->admin_feedback = $reason . " (Rejected via {$rejectedBy})";
            $invoice->save();

            SystemAuditLog::logAction(
                'PAYMENT_PROOF_REJECTED',
                "Invoice #{$invoice->invoice_number} payment proof was rejected. Reason: {$reason}",
                $invoice->user_id,
                'admin'
            );

            return [
                'success' => true,
                'message' => "Invoice #{$invoice->invoice_number} proof rejected.",
                'invoice' => $invoice,
            ];
        });
    }

    /**
     * Approve staff payout request
     */
    public static function approveStaffPayout($payoutId, $approvedBy = 'Telegram Admin')
    {
        return DB::transaction(function () use ($payoutId, $approvedBy) {
            $payout = StaffPayoutRequest::lockForUpdate()->find($payoutId);
            if (!$payout) {
                return ['success' => false, 'message' => 'Payout request not found.'];
            }

            if ($payout->status === 'approved' || $payout->status === 'paid') {
                return ['success' => false, 'message' => "Payout request #{$payout->id} is already processed."];
            }

            $payout->status = 'paid';
            $payout->processed_at = now();
            $payout->admin_notes = "Approved and marked paid via {$approvedBy} on " . now()->format('Y-m-d H:i:s');
            $payout->save();

            // Settle pending hold entry or create balancing ledger entry
            $holdEntry = StaffLedgerEntry::where('user_id', $payout->user_id)
                ->where('type', 'payout_hold')
                ->where('description', 'like', "%#{$payout->id}%")
                ->first();

            if ($holdEntry) {
                $holdEntry->type = 'payout';
                $holdEntry->status = 'paid';
                $holdEntry->description = "Payout disbursement #{$payout->id} processed via {$approvedBy}";
                $holdEntry->save();
            } else {
                StaffLedgerEntry::create([
                    'user_id' => $payout->user_id,
                    'type' => 'payout',
                    'amount' => -$payout->amount,
                    'description' => "Payout disbursement #{$payout->id} processed via {$approvedBy}",
                    'status' => 'paid',
                    'entry_date' => now(),
                ]);
            }

            return [
                'success' => true,
                'message' => "Staff payout #{$payout->id} (\${$payout->amount}) marked as paid.",
                'payout' => $payout,
            ];
        });
    }

    /**
     * Reject staff payout request
     */
    public static function rejectStaffPayout($payoutId, $reason = 'Payout request declined by Admin.', $rejectedBy = 'Telegram Admin')
    {
        return DB::transaction(function () use ($payoutId, $reason, $rejectedBy) {
            $payout = StaffPayoutRequest::lockForUpdate()->find($payoutId);
            if (!$payout) {
                return ['success' => false, 'message' => 'Payout request not found.'];
            }

            $payout->status = 'rejected';
            $payout->admin_notes = "{$reason} (Rejected via {$rejectedBy})";
            $payout->save();

            // Release any pending hold entry
            $holdEntry = StaffLedgerEntry::where('user_id', $payout->user_id)
                ->where('type', 'payout_hold')
                ->where('description', 'like', "%#{$payout->id}%")
                ->first();

            if ($holdEntry) {
                $holdEntry->delete();
            }

            return [
                'success' => true,
                'message' => "Staff payout #{$payout->id} was rejected.",
                'payout' => $payout,
            ];
        });
    }

    /**
     * Unlock client or staff user account (clears PIN lockouts, sets active)
     */
    public static function unlockUser($userIdOrEmail)
    {
        $user = is_numeric($userIdOrEmail) 
            ? User::find($userIdOrEmail) 
            : User::where('email', $userIdOrEmail)->first();

        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        $user->two_factor_enabled = false;
        $user->otp_code = null;
        $user->otp_expires_at = null;
        if (isset($user->failed_pin_attempts)) {
            $user->failed_pin_attempts = 0;
        }
        $user->save();

        SystemAuditLog::logAction(
            'USER_UNLOCKED',
            "User #{$user->id} ({$user->email}) security locks cleared via Telegram Bot.",
            $user->id,
            'admin'
        );

        return [
            'success' => true,
            'message' => "User <b>{$user->name}</b> (#{$user->id}, {$user->email}) security locks cleared and 2FA temporarily relaxed for recovery.",
            'user' => $user,
        ];
    }

    /**
     * Set or reset 4-digit security PIN for user via Telegram Bot
     */
    public static function setUserPin($userIdOrEmail, $newPin)
    {
        $user = is_numeric($userIdOrEmail) 
            ? User::find($userIdOrEmail) 
            : User::where('email', $userIdOrEmail)->first();

        if (!$user) {
            return ['success' => false, 'message' => "❌ User <code>{$userIdOrEmail}</code> not found."];
        }

        $pinStr = trim((string)$newPin);
        if (!ctype_digit($pinStr) || strlen($pinStr) !== 4) {
            return ['success' => false, 'message' => '⚠️ Security PIN must be exactly 4 numeric digits (e.g. <code>4829</code>).'];
        }

        $user->pin_hash = \Illuminate\Support\Facades\Hash::make($pinStr);
        if (isset($user->failed_pin_attempts)) {
            $user->failed_pin_attempts = 0;
        }
        $user->account_locked_until = null;
        $user->is_locked = 0;
        $user->save();

        SystemAuditLog::logAction(
            'PIN_RESET_TELEGRAM',
            "User #{$user->id} ({$user->email}) 4-digit Security PIN updated via Telegram Bot.",
            $user->id,
            'admin'
        );

        return [
            'success' => true,
            'message' => "🔒 Security PIN for <b>{$user->name}</b> (#{$user->id}, {$user->email}) successfully updated to <code>{$pinStr}</code>.",
            'user' => $user,
        ];
    }

    /**
     * Post a reply message from Telegram into the client conversation thread
     */
    public static function sendClientReplyFromTelegram($conversationId, $replyText, $telegramMessageId = null)
    {
        try {
            $conversation = Conversation::find($conversationId);
            if (!$conversation) {
                return ['success' => false, 'message' => 'Conversation thread not found.'];
            }

            // Get admin user (ID 1 or first admin)
            $adminUser = User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })->first() ?: User::find(1);

            $adminId = $adminUser ? $adminUser->id : 1;

            $msgData = [
                'conversation_id' => $conversation->id,
                'user_id' => $adminId,
                'text' => $replyText,
                'read' => false,
            ];
            if (\Illuminate\Support\Facades\Schema::hasColumn('messages', 'message')) {
                $msgData['message'] = $replyText;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('messages', 'telegram_message_id')) {
                $msgData['telegram_message_id'] = $telegramMessageId;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('messages', 'is_telegram_reply')) {
                $msgData['is_telegram_reply'] = true;
            }
            $msg = Message::create($msgData);

            $conversation->touch();

            return [
                'success' => true,
                'message' => "Reply successfully posted to Client conversation #{$conversation->slug}.",
                'data' => $msg,
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Failed to deliver reply: ' . $e->getMessage()];
        }
    }

    /**
     * Post a reply message from Telegram into staff_messages
     */
    public static function sendStaffReplyFromTelegram($staffUserId, $replyText, $telegramMessageId = null)
    {
        $staffUser = User::find($staffUserId);
        if (!$staffUser) {
            return ['success' => false, 'message' => 'Staff user not found.'];
        }

        $adminUser = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->first() ?: User::find(1);
        $adminId = $adminUser ? $adminUser->id : 1;

        $staffData = [
            'staff_user_id' => $staffUser->id,
            'officer_user_id' => $adminId,
            'sender_id' => $adminId,
            'message' => $replyText,
            'read' => false,
        ];
        if (\Illuminate\Support\Facades\Schema::hasColumn('staff_messages', 'telegram_message_id')) {
            $staffData['telegram_message_id'] = $telegramMessageId;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('staff_messages', 'is_telegram_reply')) {
            $staffData['is_telegram_reply'] = true;
        }

        $msg = StaffMessage::create($staffData);

        return [
            'success' => true,
            'message' => "Reply successfully sent to Staff member {$staffUser->name}.",
            'data' => $msg,
        ];
    }

    /**
     * Approve staff submitted task
     */
    public static function approveStaffTask($taskId, $approvedBy = 'Telegram Admin')
    {
        $task = \App\Models\StaffTask::with('user')->find($taskId);
        if (!$task) {
            return ['success' => false, 'message' => "Task #{$taskId} not found."];
        }

        $task->status = 'completed';
        if (\Illuminate\Support\Facades\Schema::hasColumn('staff_tasks', 'approved_at')) {
            $task->approved_at = now();
        }
        $task->save();

        SystemAuditLog::logAction(
            'STAFF_TASK_APPROVED',
            "Staff Task #{$task->id} '{$task->title}' approved by {$approvedBy}.",
            $task->user_id,
            'admin'
        );

        // Notify staff via email
        try {
            if ($task->user && $task->user->email) {
                Mail::raw("Hello {$task->user->name},\n\nYour submitted deliverables for task: '{$task->title}' have been reviewed and approved by management.\n\nThank you for your high-standard execution.\n\nYour CPA Expert Practice Management", function ($m) use ($task) {
                    $m->to($task->user->email)->subject("Task Approved: {$task->title}");
                });
            }
        } catch (\Throwable $e) {}

        return [
            'success' => true,
            'message' => "Task #{$task->id} ('{$task->title}') approved successfully for {$task->user?->name}.",
            'task' => $task
        ];
    }

    /**
     * Reject / Return staff task for revisions
     */
    public static function rejectStaffTask($taskId, $reason = 'Task returned for review & revisions.', $rejectedBy = 'Telegram Admin')
    {
        $task = \App\Models\StaffTask::with('user')->find($taskId);
        if (!$task) {
            return ['success' => false, 'message' => "Task #{$taskId} not found."];
        }

        $task->status = 'pending';
        $task->save();

        SystemAuditLog::logAction(
            'STAFF_TASK_REJECTED',
            "Staff Task #{$task->id} '{$task->title}' returned for revisions by {$rejectedBy}. Reason: {$reason}",
            $task->user_id,
            'admin'
        );

        // Notify staff via email
        try {
            if ($task->user && $task->user->email) {
                Mail::raw("Hello {$task->user->name},\n\nYour submitted task: '{$task->title}' has been returned for revisions.\n\nFeedback / Notes: {$reason}\n\nPlease review your workspace and submit updated deliverables once addressed.\n\nYour CPA Expert Operations", function ($m) use ($task) {
                    $m->to($task->user->email)->subject("Task Revision Required: {$task->title}");
                });
            }
        } catch (\Throwable $e) {}

        return [
            'success' => true,
            'message' => "Task #{$task->id} ('{$task->title}') returned for revisions to {$task->user?->name}.",
            'task' => $task
        ];
    }
}

