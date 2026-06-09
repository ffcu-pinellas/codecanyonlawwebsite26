<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\StaffDetail;
use App\Models\StaffLoginLog;
use App\Models\StaffMessage;
use App\Models\StaffTimeLog;
use App\Models\StaffTask;
use App\Models\StaffPayoutRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AdminStaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
        parent::__construct();
    }

    /**
     * Helper to send raw email notifications
     */
    protected function getHtmlEmailWrapper($subject, $bodyText)
    {
        $appName = env('APP_NAME', 'Your CPA Expert');
        $bodyHtml = nl2br(e($bodyText));

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$subject}</title>
    <style>
        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #e1e8ed;
        }
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 25px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 30px 25px;
            line-height: 1.6;
            font-size: 15px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777777;
            border-top: 1px solid #eeeeee;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{$appName}</h1>
        </div>
        <div class="content">
            {$bodyHtml}
        </div>
        <div class="footer">
            <p><strong>&copy; 2026 {$appName}.</strong> All Rights Reserved.</p>
            <p style="font-style: italic;">This is an automated notification. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    protected function sendEmailNotification($to, $subject, $body)
    {
        try {
            $htmlContent = $this->getHtmlEmailWrapper($subject, $body);
            Mail::html($htmlContent, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });
        } catch (\Throwable $e) {
            // Silent fallback if email is not configured
        }
    }

    /**
     * Calculate next payday automatically based on schedule
     */
    protected function calculateNextPayDate($hiredAt, $paySchedule)
    {
        $hiredDate = Carbon::parse($hiredAt);
        $today = Carbon::today();
        
        switch ($paySchedule) {
            case 'weekly':
                while ($hiredDate->lte($today)) {
                    $hiredDate->addWeek();
                }
                break;
            case 'bi-weekly':
                while ($hiredDate->lte($today)) {
                    $hiredDate->addWeeks(2);
                }
                break;
            case 'monthly':
                while ($hiredDate->lte($today)) {
                    $hiredDate->addMonth();
                }
                break;
            case 'quarterly':
                while ($hiredDate->lte($today)) {
                    $hiredDate->addMonths(3);
                }
                break;
            case 'semi-monthly':
                // semi-monthly usually pays on 15th and last day of month
                if ($today->day <= 15) {
                    $hiredDate = Carbon::create($today->year, $today->month, 15);
                } else {
                    $hiredDate = Carbon::create($today->year, $today->month, 1)->endOfMonth();
                }
                if ($hiredDate->lte($today)) {
                    $hiredDate = $today->day <= 15 
                        ? Carbon::create($today->year, $today->month, 1)->endOfMonth() 
                        : Carbon::create($today->year, $today->month, 15)->addMonth();
                }
                break;
        }
        return $hiredDate;
    }

    /**
     * Display list of all staff
     */
    public function index()
    {
        try {
            $staffUsers = User::role('staff')->with('staffDetail')->get();
            $title = __('Staff Directory');

            return view('backend.pages.staff.index', compact('staffUsers', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show form to create staff
     */
    public function create()
    {
        try {
            $title = __('Add Staff Member');
            $officers = User::role('admin')->get();
            $staff = null;

            return view('backend.pages.staff.form', compact('title', 'officers', 'staff'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store new staff
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'hired_at' => 'required|date',
            'pay_schedule' => 'required|string|in:weekly,bi-weekly,monthly,semi-monthly,quarterly',
            'next_pay_date' => 'nullable|date',
            'assigned_officer_id' => 'nullable|exists:users,id',
            'is_active' => 'required|boolean',
            'bonus' => 'nullable|numeric|min:0',
            'debt' => 'nullable|numeric|min:0',
            'reimbursement' => 'nullable|numeric|min:0',
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone ?: '',
                'address' => $request->address ?: '',
            ]);

            // Assign role
            $user->assignRole('staff');

            // Generate unique Staff ID
            do {
                $randomNum = rand(100000, 999999);
                $staffId = 'STF-' . $randomNum;
            } while (StaffDetail::where('staff_id', $staffId)->exists());

            // Compute payday if left blank
            $nextPayDate = $request->next_pay_date;
            if (empty($nextPayDate)) {
                $nextPayDate = $this->calculateNextPayDate($request->hired_at, $request->pay_schedule);
            }

            // Create staff details
            StaffDetail::create([
                'user_id' => $user->id,
                'staff_id' => $staffId,
                'position' => $request->position,
                'hourly_rate' => $request->hourly_rate,
                'hired_at' => $request->hired_at,
                'pay_schedule' => $request->pay_schedule,
                'next_pay_date' => $nextPayDate,
                'assigned_officer_id' => $request->assigned_officer_id,
                'is_active' => $request->is_active,
                'bonus' => $request->bonus ?: 0.00,
                'debt' => $request->debt ?: 0.00,
                'reimbursement' => $request->reimbursement ?: 0.00,
            ]);

            return redirect()->route('admin.staff.index')->with('success', __('Staff member registered successfully. ID: ' . $staffId));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show form to edit staff
     */
    public function edit($id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            
            if (!$staff->staffDetail) {
                do {
                    $randomNum = rand(100000, 999999);
                    $staffId = 'STF-' . $randomNum;
                } while (StaffDetail::where('staff_id', $staffId)->exists());

                StaffDetail::create([
                    'user_id' => $staff->id,
                    'staff_id' => $staffId,
                    'hired_at' => now(),
                    'is_active' => true,
                ]);
                $staff->load('staffDetail');
            }

            $title = __('Edit Staff Member');
            $officers = User::role('admin')->get();

            return view('backend.pages.staff.form', compact('title', 'officers', 'staff'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update staff details
     */
    public function update(Request $request, $id)
    {
        $staff = User::role('staff')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'hired_at' => 'required|date',
            'pay_schedule' => 'required|string|in:weekly,bi-weekly,monthly,semi-monthly,quarterly',
            'next_pay_date' => 'nullable|date',
            'assigned_officer_id' => 'nullable|exists:users,id',
            'is_active' => 'required|boolean',
            'bonus' => 'nullable|numeric|min:0',
            'debt' => 'nullable|numeric|min:0',
            'reimbursement' => 'nullable|numeric|min:0',
        ]);

        try {
            // Update User
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone ?: '',
                'address' => $request->address ?: '',
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $staff->update($userData);

            // Compute payday if schedule changed or next_pay_date is blank
            $nextPayDate = $request->next_pay_date;
            if (empty($nextPayDate)) {
                $nextPayDate = $this->calculateNextPayDate($request->hired_at, $request->pay_schedule);
            }

            // Update Staff Detail
            $staff->staffDetail->update([
                'position' => $request->position,
                'hourly_rate' => $request->hourly_rate,
                'hired_at' => $request->hired_at,
                'pay_schedule' => $request->pay_schedule,
                'next_pay_date' => $nextPayDate,
                'assigned_officer_id' => $request->assigned_officer_id,
                'is_active' => $request->is_active,
                'bonus' => $request->bonus ?: 0.00,
                'debt' => $request->debt ?: 0.00,
                'reimbursement' => $request->reimbursement ?: 0.00,
            ]);

            return redirect()->route('admin.staff.index')->with('success', __('Staff member updated successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove staff
     */
    public function destroy($id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $staff->delete();

            return redirect()->route('admin.staff.index')->with('success', __('Staff member deleted successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display staff check-in history
     */
    public function timeLogs($id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $timeLogs = $staff->staffTimeLogs()->orderBy('clocked_in_at', 'desc')->get();
            $title = __('Time & Wage Logs for ') . $staff->name;

            return view('backend.pages.staff.time-logs', compact('staff', 'timeLogs', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display staff login audit trail
     */
    public function loginLogs($id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $loginLogs = $staff->staffLoginLogs()->orderBy('logged_in_at', 'desc')->get();
            $title = __('Login Logs for ') . $staff->name;

            return view('backend.pages.staff.login-logs', compact('staff', 'loginLogs', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * View messages / Chat panel with staff
     */
    public function messages($id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $adminUser = Auth::user();

            $messages = StaffMessage::where('staff_user_id', $staff->id)
                ->where('officer_user_id', $adminUser->id)
                ->orderBy('created_at', 'asc')
                ->get();

            StaffMessage::where('staff_user_id', $staff->id)
                ->where('officer_user_id', $adminUser->id)
                ->where('sender_id', $staff->id)
                ->where('read', false)
                ->update(['read' => true]);

            $title = __('Chat with ') . $staff->name;

            return view('backend.pages.staff.messages', compact('staff', 'messages', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Send message to staff
     */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            $staff = User::role('staff')->findOrFail($id);
            $adminUser = Auth::user();

            $chat = StaffMessage::create([
                'staff_user_id' => $staff->id,
                'officer_user_id' => $adminUser->id,
                'sender_id' => $adminUser->id,
                'message' => $request->message,
                'read' => false,
            ]);

            // Email Alert to Staff
            if ($staff && $staff->email) {
                $this->sendEmailNotification(
                    $staff->email, 
                    __('New Message from Admin/Officer'),
                    __('Your officer ') . $adminUser->name . __(' sent a message: ') . "\n\n" . $request->message
                );
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'id' => $chat->id,
                    'message' => $chat->message,
                    'created_at' => $chat->created_at->format('M d, h:i A')
                ]);
            }

            return $this->backWithSuccess(__('Message sent successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Download payment forms uploaded by staff
     */
    public function downloadPaymentForm($id, $type)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $detail = $staff->staffDetail;

            if (!$detail) {
                abort(404, 'Staff details not found.');
            }

            $filePath = '';
            if ($type === 'void_check') {
                $filePath = public_path($detail->void_check_path);
            } elseif ($type === 'direct_deposit') {
                $filePath = public_path($detail->direct_deposit_form_path);
            } else {
                abort(404, 'Invalid document type.');
            }

            if (empty($filePath) || !file_exists($filePath)) {
                return $this->backWithError(__('The requested document was not found or has not been uploaded.'));
            }

            return response()->download($filePath);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Verify payment details
     */
    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'payment_verified' => 'required|boolean',
        ]);

        try {
            $staff = User::role('staff')->findOrFail($id);
            $detail = $staff->staffDetail;

            if (!$detail) {
                abort(404, 'Staff details not found.');
            }

            $detail->update([
                'payment_verified' => $request->payment_verified,
            ]);

            // Notify Staff via Email
            if ($staff && $staff->email) {
                $statusStr = $request->payment_verified ? __('APPROVED') : __('REJECTED');
                $this->sendEmailNotification(
                    $staff->email,
                    __('Payment Verification Status Changed'),
                    __('Your uploaded payment preferences documents have been ') . $statusStr . __(' by the administrator.')
                );
            }

            $statusStr = $request->payment_verified ? __('verified') : __('unverified');
            return $this->backWithSuccess(__('Staff payment details marked as ') . $statusStr);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * List all tasks and assign tasks
     */
    public function tasksIndex()
    {
        $tasks = StaffTask::with('user')->orderBy('created_at', 'desc')->get();
        $staffUsers = User::role('staff')->get();
        $title = __('Corporate Tasks');

        return view('backend.pages.staff.tasks', compact('tasks', 'staffUsers', 'title'));
    }

    /**
     * Store new task and alert staff via email
     */
    public function tasksStore(Request $request)
    {
        $request->validate([
            'staff_user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $task = StaffTask::create([
            'staff_user_id' => $request->staff_user_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        // Email Alert to Staff
        $staff = User::findOrFail($request->staff_user_id);
        if ($staff && $staff->email) {
            $this->sendEmailNotification(
                $staff->email,
                __('New Corporate Task Assigned: ') . $request->title,
                __('You have been assigned a new task: "') . $request->title . "\"\n" .
                __('Description: ') . $request->description . "\n" .
                __('Due Date: ') . ($request->due_date ? Carbon::parse($request->due_date)->format('M d, Y') : __('No deadline'))
            );
        }

        return $this->backWithSuccess(__('Task created and staff notified via email.'));
    }

    /**
     * Update task status (e.g. Approve completion)
     */
    public function tasksStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,approved',
        ]);

        $task = StaffTask::findOrFail($id);
        $task->update(['status' => $request->status]);

        // Email Alert to Staff
        $staff = $task->user;
        if ($staff && $staff->email) {
            $this->sendEmailNotification(
                $staff->email,
                __('Task Status Update: ') . $task->title,
                __('Your assigned task "') . $task->title . __('" status has been updated to: ') . strtoupper($request->status)
            );
        }

        return $this->backWithSuccess(__('Task status updated to ') . $request->status);
    }

    /**
     * Delete corporate task
     */
    public function tasksDestroy($id)
    {
        $task = StaffTask::findOrFail($id);
        $task->delete();

        return $this->backWithSuccess(__('Task deleted successfully.'));
    }

    /**
     * List Payout Requests
     */
    public function payoutsIndex()
    {
        $payoutRequests = StaffPayoutRequest::with('user')->orderBy('created_at', 'desc')->get();
        $title = __('Staff Payout Requests');

        return view('backend.pages.staff.payouts', compact('payoutRequests', 'title'));
    }

    /**
     * Update payout request status (Approve / Mark paid)
     */
    public function payoutsStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,paid',
        ]);

        $payout = StaffPayoutRequest::findOrFail($id);
        $payout->update(['status' => $request->status]);

        // Email Alert to Staff
        $staff = $payout->user;
        if ($staff && $staff->email) {
            $this->sendEmailNotification(
                $staff->email,
                __('Payout Request Update - Status: ') . strtoupper($request->status),
                __('Your request for a payout of $') . number_format($payout->amount, 2) . __(' has been marked as: ') . strtoupper($request->status)
            );
        }

        return $this->backWithSuccess(__('Payout request marked as ') . $request->status);
    }

    /**
     * Poll messages since last_id
     */
    public function pollMessages(Request $request, $id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $adminUser = Auth::user();
            $lastId = intval($request->get('last_id', 0));

            $newMessages = StaffMessage::where('staff_user_id', $staff->id)
                ->where('officer_user_id', $adminUser->id)
                ->where('id', '>', $lastId)
                ->orderBy('id', 'asc')
                ->get();

            // Mark received messages as read
            StaffMessage::where('staff_user_id', $staff->id)
                ->where('officer_user_id', $adminUser->id)
                ->where('sender_id', $staff->id)
                ->where('read', false)
                ->update(['read' => true]);

            $data = [];
            foreach ($newMessages as $msg) {
                $data[] = [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'is_sent' => ($msg->sender_id === $adminUser->id),
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->format('M d, h:i A')
                ];
            }

            return response()->json(['success' => true, 'messages' => $data]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Manage itemized ledger entries
     */
    public function ledgerIndex($id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $entries = $staff->staffLedgerEntries()->orderBy('entry_date', 'desc')->orderBy('id', 'desc')->get();
            $title = __('Financial Ledger for ') . $staff->name;

            return view('backend.pages.staff.ledger', compact('staff', 'entries', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Helper to recalculate and update staff details totals
     */
    protected function recalculateStaffLedgerTotals($staff)
    {
        $detail = $staff->staffDetail;
        if ($detail) {
            $totals = $staff->staffLedgerEntries()
                ->where('status', '!=', 'pending')
                ->selectRaw("
                    SUM(CASE WHEN type = 'reimbursement' THEN (amount - paid_amount) ELSE 0 END) as total_reim,
                    SUM(CASE WHEN type = 'debt' THEN (amount - paid_amount) ELSE 0 END) as total_debt,
                    SUM(CASE WHEN type = 'bonus' THEN (amount - paid_amount) ELSE 0 END) as total_bonus
                ")
                ->first();
            $detail->update([
                'reimbursement' => max(0, $totals->total_reim ?: 0.00),
                'debt' => max(0, $totals->total_debt ?: 0.00),
                'bonus' => max(0, $totals->total_bonus ?: 0.00),
            ]);
        }
    }

    /**
     * Store new ledger entry
     */
    public function ledgerStore(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:debt,reimbursement,bonus',
            'amount' => 'required|numeric|min:0.01',
            'paid_amount' => 'nullable|numeric|min:0|max:' . $request->amount,
            'description' => 'required|string|max:255',
            'entry_date' => 'required|date',
            'attachment' => 'nullable|file|mimes:pdf,jpeg,png,jpg,zip|max:10240',
        ]);

        try {
            $staff = User::role('staff')->findOrFail($id);

            $amount = floatval($request->amount);
            $paidAmount = floatval($request->get('paid_amount', 0));
            $status = 'approved';
            if ($paidAmount >= $amount) {
                $status = 'paid';
            } elseif ($paidAmount > 0) {
                $status = 'partially_paid';
            }

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $filename = 'ledger_' . time() . '_' . uniqid() . '.' . $request->attachment->getClientOriginalExtension();
                $request->attachment->move(public_path('upload/staff-ledger'), $filename);
                $attachmentPath = 'upload/staff-ledger/' . $filename;
            }

            \App\Models\StaffLedgerEntry::create([
                'user_id' => $staff->id,
                'type' => $request->type,
                'amount' => $amount,
                'paid_amount' => $paidAmount,
                'status' => $status,
                'attachment_path' => $attachmentPath,
                'description' => $request->description,
                'entry_date' => $request->entry_date,
                'created_by' => 'admin',
            ]);

            $this->recalculateStaffLedgerTotals($staff);

            return redirect()->route('admin.staff.ledger.index', $staff->id)->with('success', __('Ledger entry added successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Approve a staff pending request
     */
    public function ledgerApprove(Request $request, $id, $entryId)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $entry = \App\Models\StaffLedgerEntry::where('user_id', $staff->id)->findOrFail($entryId);
            
            $request->validate([
                'paid_amount' => 'nullable|numeric|min:0|max:' . $entry->amount,
            ]);

            $paidAmount = floatval($request->get('paid_amount', 0));
            $status = 'approved';
            if ($paidAmount >= $entry->amount) {
                $status = 'paid';
            } elseif ($paidAmount > 0) {
                $status = 'partially_paid';
            }

            $entry->update([
                'paid_amount' => $paidAmount,
                'status' => $status,
            ]);

            $this->recalculateStaffLedgerTotals($staff);

            return redirect()->back()->with('success', __('Ledger entry approved successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Record a payment/settlement on an approved entry
     */
    public function ledgerPay(Request $request, $id, $entryId)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $entry = \App\Models\StaffLedgerEntry::where('user_id', $staff->id)->findOrFail($entryId);

            $maxPayable = $entry->amount - $entry->paid_amount;
            $request->validate([
                'payment_amount' => 'required|numeric|min:0.01|max:' . $maxPayable,
            ]);

            $paymentAmount = floatval($request->payment_amount);
            $newPaid = $entry->paid_amount + $paymentAmount;
            $status = 'partially_paid';
            if ($newPaid >= $entry->amount) {
                $status = 'paid';
            }

            $entry->update([
                'paid_amount' => $newPaid,
                'status' => $status,
            ]);

            $this->recalculateStaffLedgerTotals($staff);

            return redirect()->back()->with('success', __('Payment recorded successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Download proof documents uploaded in the ledger
     */
    public function downloadLedgerProof($entryId)
    {
        try {
            $entry = \App\Models\StaffLedgerEntry::findOrFail($entryId);
            
            $filePath = public_path($entry->attachment_path);
            if (empty($entry->attachment_path) || !file_exists($filePath)) {
                return redirect()->back()->with('error', __('The proof document was not found or has not been uploaded.'));
            }

            return response()->download($filePath);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete ledger entry
     */
    public function ledgerDestroy($id, $entryId)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $entry = \App\Models\StaffLedgerEntry::where('user_id', $staff->id)->findOrFail($entryId);
            $entry->delete();

            $this->recalculateStaffLedgerTotals($staff);

            return redirect()->route('admin.staff.ledger.index', $staff->id)->with('success', __('Ledger entry deleted successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
