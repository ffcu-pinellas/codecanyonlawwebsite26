<?php

namespace App\Http\Controllers\StaffControllers;

use App\Http\Controllers\Controller;
use App\Models\StaffDetail;
use App\Models\StaffTimeLog;
use App\Models\StaffLoginLog;
use App\Models\StaffMessage;
use App\Models\StaffTask;
use App\Models\StaffPayoutRequest;
use App\Models\User;
use App\Models\DocumentTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class StaffViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLoginForm', 'login']);
        parent::__construct();
    }



    /**
     * Show the staff login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('staff')) {
                return redirect()->route('staff.dashboard');
            }
            return redirect()->route('home');
        }
        $title = __('Staff Login');
        return view('frontend.theme1.auth-staff.login', compact('title'));
    }

    /**
     * Authenticate the staff member
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            if (!$user->hasRole('staff')) {
                Auth::logout();
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => __('This login portal is only for staff members.')]);
            }

            $staffDetail = $user->staffDetail;
            if (!$staffDetail || !$staffDetail->is_active) {
                Auth::logout();
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => __('Your staff account is inactive. Please contact your administrator.')]);
            }

            // IP Geolocation Location resolver
            $location = 'Unknown';
            try {
                $ip = $request->ip();
                if ($ip !== '127.0.0.1' && $ip !== '::1') {
                    $ctx = stream_context_create(['http' => ['timeout' => 2]]);
                    $res = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city", false, $ctx);
                    if ($res) {
                        $geo = json_decode($res, true);
                        if ($geo && $geo['status'] === 'success') {
                            $location = ($geo['city'] ?? '') . ', ' . ($geo['regionName'] ?? '') . ', ' . ($geo['country'] ?? '');
                        }
                    }
                } else {
                    $location = 'Local Host';
                }
            } catch (\Throwable $e) {}

            StaffLoginLog::create([
                'user_id' => $user->id,
                'logged_in_at' => now(),
                'ip_address' => $request->ip(),
                'location' => $location,
                'user_agent' => $request->userAgent(),
            ]);

            // Telegram Notification
            $telegramMsg = "🔔 <b>Staff Login Alert</b>\n"
                . "👤 <b>Staff:</b> {$user->name}\n"
                . "🆔 <b>Staff ID:</b> " . ($staffDetail ? $staffDetail->staff_id : 'N/A') . "\n"
                . "📅 <b>Time:</b> " . now()->format('M d, Y h:i A') . "\n"
                . "📍 <b>Location:</b> {$location}\n"
                . "🖥️ <b>IP:</b> " . $request->ip();
            $this->sendTelegramNotification($telegramMsg);

            return redirect()->intended(route('staff.dashboard'))
                ->with('success', __('Welcome back to the Staff Portal!'));
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __('Invalid credentials.')]);
    }

    /**
     * Staff Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $staffDetail = $user->staffDetail;

        if (!$staffDetail) {
            do {
                $randomNum = rand(100000, 999999);
                $staffId = 'STF-' . $randomNum;
            } while (StaffDetail::where('staff_id', $staffId)->exists());

            $staffDetail = StaffDetail::create([
                'user_id' => $user->id,
                'staff_id' => $staffId,
                'is_active' => true,
                'hired_at' => now(),
            ]);
        }

        $activeLog = $user->staffTimeLogs()->whereNull('clocked_out_at')->first();
        $timeLogs = $user->staffTimeLogs()->whereNotNull('clocked_out_at')->orderBy('clocked_in_at', 'desc')->get();
        
        $totalDurationSeconds = $timeLogs->sum('duration_seconds');
        $totalEarned = $timeLogs->sum('earned_amount');
        $totalHoursWorked = round($totalDurationSeconds / 3600, 2);

        $officer = $staffDetail->officer;
        if (!$officer) {
            $officer = User::role('admin')->first() ?: User::first();
        }

        // Weekly hours worked chart data (last 7 days)
        $weeklyHours = [];
        $weeklyDays = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $weeklyDays[] = $day->format('M d');
            
            $daySeconds = $user->staffTimeLogs()
                ->whereNotNull('clocked_out_at')
                ->whereDate('clocked_in_at', $day)
                ->sum('duration_seconds');
                
            $weeklyHours[] = round($daySeconds / 3600, 2);
        }

        $title = __('Staff Dashboard');

        return view('frontend.theme1.auth-staff.dashboard', compact(
            'title',
            'user',
            'staffDetail',
            'activeLog',
            'timeLogs',
            'totalHoursWorked',
            'totalEarned',
            'officer',
            'weeklyDays',
            'weeklyHours'
        ));
    }

    /**
     * Clock In Action
     */
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $staffDetail = $user->staffDetail;

        if (!$staffDetail || !$staffDetail->is_active) {
            Auth::logout();
            return redirect()->route('staff.login')->with('error', __('Account is inactive.'));
        }

        $activeLog = $user->staffTimeLogs()->whereNull('clocked_out_at')->first();
        if ($activeLog) {
            return $this->backWithError(__('You are already clocked in.'));
        }

        StaffTimeLog::create([
            'user_id' => $user->id,
            'clocked_in_at' => now(),
            'hourly_rate_at_time' => $staffDetail->hourly_rate,
            'earned_amount' => 0.00,
            'clock_in_ip' => $request->ip(),
            'clock_in_latitude' => $request->input('latitude'),
            'clock_in_longitude' => $request->input('longitude'),
        ]);

        // Send email to Officer/Admin
        $officer = $staffDetail->officer ?: (User::role('admin')->first() ?: User::first());
        if ($officer && $officer->email) {
            $this->sendEmailNotification(
                $officer->email, 
                __('Staff Clocked In Alert - ') . $user->name,
                __('Employee ') . $user->name . __(' clocked in on ') . now()->format('Y-m-d H:i:s')
            );
        }

        // Telegram Notification
        $telegramMsg = "🟢 <b>Staff Clocked In</b>\n"
            . "👤 <b>Staff:</b> {$user->name}\n"
            . "🆔 <b>Staff ID:</b> " . ($staffDetail ? $staffDetail->staff_id : 'N/A') . "\n"
            . "📅 <b>Time:</b> " . now()->format('M d, Y h:i A');
        $this->sendTelegramNotification($telegramMsg);

        return $this->backWithSuccess(__('Clocked in successfully.'));
    }

    /**
     * Clock Out Action
     */
    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $staffDetail = $user->staffDetail;

        $activeLog = $user->staffTimeLogs()->whereNull('clocked_out_at')->first();
        if (!$activeLog) {
            return $this->backWithError(__('You are not clocked in.'));
        }

        $clockedOutAt = now();
        $durationSeconds = Carbon::parse($activeLog->clocked_in_at)->diffInSeconds($clockedOutAt);
        $hourlyRate = $activeLog->hourly_rate_at_time;
        $earnedAmount = round(($durationSeconds / 3600) * $hourlyRate, 2);

        $activeLog->update([
            'clocked_out_at' => $clockedOutAt,
            'duration_seconds' => $durationSeconds,
            'earned_amount' => $earnedAmount,
            'clock_out_ip' => $request->ip(),
            'clock_out_latitude' => $request->input('latitude'),
            'clock_out_longitude' => $request->input('longitude'),
        ]);

        // Send email to Officer/Admin
        $officer = $staffDetail->officer ?: (User::role('admin')->first() ?: User::first());
        if ($officer && $officer->email) {
            $this->sendEmailNotification(
                $officer->email, 
                __('Staff Clocked Out Alert - ') . $user->name,
                __('Employee ') . $user->name . __(' clocked out on ') . now()->format('Y-m-d H:i:s') . __('. Duration: ') . round($durationSeconds / 60, 1) . __(' minutes.')
            );
        }

        // Telegram Notification
        $telegramMsg = "🔴 <b>Staff Clocked Out</b>\n"
            . "👤 <b>Staff:</b> {$user->name}\n"
            . "🆔 <b>Staff ID:</b> " . ($staffDetail ? $staffDetail->staff_id : 'N/A') . "\n"
            . "📅 <b>Time:</b> " . now()->format('M d, Y h:i A') . "\n"
            . "⏱️ <b>Duration:</b> " . round($durationSeconds / 60, 1) . " mins (" . round($durationSeconds / 3600, 2) . " hrs)\n"
            . "💵 <b>Wages Earned:</b> $" . number_format($earnedAmount, 2);
        $this->sendTelegramNotification($telegramMsg);

        return $this->backWithSuccess(__('Clocked out successfully. Worked for ' . round($durationSeconds / 60, 1) . ' minutes. Earned $' . number_format($earnedAmount, 2)));
    }

    /**
     * Payment Preferences View
     */
    public function paymentMethod()
    {
        $user = Auth::user();
        $staffDetail = $user->staffDetail;
        $title = __('Payment Preferences');

        return view('frontend.theme1.auth-staff.payment-method', compact('title', 'user', 'staffDetail'));
    }

    /**
     * Update Payment Preferences & Upload documents
     */
    public function updatePaymentMethod(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:paycheck,direct_deposit',
            'check_name' => 'required_if:payment_method,paycheck|nullable|string|max:255',
            'check_address' => 'required_if:payment_method,paycheck|nullable|string',
            'bank_name' => 'required_if:payment_method,direct_deposit|nullable|string|max:255',
            'account_name' => 'required_if:payment_method,direct_deposit|nullable|string|max:255',
            'account_number' => 'required_if:payment_method,direct_deposit|nullable|string|max:255',
            'routing_number' => 'required_if:payment_method,direct_deposit|nullable|string|max:255',
            'void_check' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'direct_deposit_form' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $user = Auth::user();
        $staffDetail = $user->staffDetail;

        $updateData = [
            'payment_method' => $request->payment_method,
            'check_name' => $request->payment_method === 'paycheck' ? $request->check_name : null,
            'check_address' => $request->payment_method === 'paycheck' ? $request->check_address : null,
            'bank_name' => $request->payment_method === 'direct_deposit' ? $request->bank_name : null,
            'account_name' => $request->payment_method === 'direct_deposit' ? $request->account_name : null,
            'account_number' => $request->payment_method === 'direct_deposit' ? $request->account_number : null,
            'routing_number' => $request->payment_method === 'direct_deposit' ? $request->routing_number : null,
        ];

        $docUploaded = false;

        if ($request->hasFile('void_check')) {
            $filename = 'void_check_' . $user->id . '_' . time() . '.' . $request->void_check->getClientOriginalExtension();
            $request->void_check->move(public_path('upload/staff-documents'), $filename);
            $updateData['void_check_path'] = 'upload/staff-documents/' . $filename;
            $docUploaded = true;
        }

        if ($request->hasFile('direct_deposit_form')) {
            $filename = 'direct_deposit_' . $user->id . '_' . time() . '.' . $request->direct_deposit_form->getClientOriginalExtension();
            $request->direct_deposit_form->move(public_path('upload/staff-documents'), $filename);
            $updateData['direct_deposit_form_path'] = 'upload/staff-documents/' . $filename;
            $docUploaded = true;
        }

        $infoChanged = ($staffDetail->payment_method !== $request->payment_method) ||
                       ($staffDetail->check_name !== $request->check_name) ||
                       ($staffDetail->check_address !== $request->check_address) ||
                       ($staffDetail->bank_name !== $request->bank_name) ||
                       ($staffDetail->account_name !== $request->account_name) ||
                       ($staffDetail->account_number !== $request->account_number) ||
                       ($staffDetail->routing_number !== $request->routing_number);

        if ($docUploaded || $infoChanged) {
            $updateData['payment_verified'] = false;
        }

        $staffDetail->update($updateData);

        return redirect()->route('staff.dashboard')->with('success', __('Payment preferences updated successfully. Documents are awaiting administrator verification.'));
    }

    /**
     * Messaging View (Assigned Officer Chat)
     */
    public function messages()
    {
        $user = Auth::user();
        $staffDetail = $user->staffDetail;
        
        $officer = $staffDetail->officer;
        if (!$officer) {
            $officer = User::role('admin')->first() ?: User::first();
        }

        $messages = StaffMessage::where(function ($query) use ($user, $officer) {
                $query->where('staff_user_id', $user->id)
                      ->where('officer_user_id', $officer->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        StaffMessage::where('staff_user_id', $user->id)
            ->where('officer_user_id', $officer->id)
            ->where('sender_id', $officer->id)
            ->where('read', false)
            ->update(['read' => true]);

        $title = __('Officer Chat');

        return view('frontend.theme1.auth-staff.messages', compact('title', 'user', 'staffDetail', 'officer', 'messages'));
    }

    /**
     * Send Message to Officer
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $staffDetail = $user->staffDetail;

        $officer = $staffDetail->officer;
        if (!$officer) {
            $officer = User::role('admin')->first() ?: User::first();
        }

        $chat = StaffMessage::create([
            'staff_user_id' => $user->id,
            'officer_user_id' => $officer->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'read' => false,
        ]);

        // Send email to Officer
        if ($officer && $officer->email) {
            $this->sendEmailNotification(
                $officer->email, 
                __('New Staff Message from ') . $user->name,
                __('Employee ') . $user->name . __(' sent a message: ') . "\n\n" . $request->message
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

        return $this->backWithSuccess(__('Message sent.'));
    }

    /**
     * Staff Tasks Index
     */
    public function tasksIndex()
    {
        $user = Auth::user();
        $tasks = $user->staffTasks()->orderBy('created_at', 'desc')->get();
        $title = __('Task Management');

        return view('frontend.theme1.auth-staff.tasks', compact('title', 'user', 'tasks'));
    }

    /**
     * Complete Task Submission
     */
    public function tasksComplete(Request $request, $id)
    {
        $task = StaffTask::where('staff_user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'completion_notes' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,docx,doc,jpeg,png,jpg,zip|max:10240',
        ]);

        $updateData = [
            'completion_notes' => $request->completion_notes,
            'status' => 'completed',
        ];

        if ($request->hasFile('attachment')) {
            $filename = 'task_submission_' . $task->id . '_' . time() . '.' . $request->attachment->getClientOriginalExtension();
            $request->attachment->move(public_path('upload/staff-tasks'), $filename);
            $updateData['attachment_path'] = 'upload/staff-tasks/' . $filename;
        }

        $task->update($updateData);

        // Notify Assigned Officer via Email
        $officer = Auth::user()->staffDetail->officer ?: (User::role('admin')->first() ?: User::first());
        if ($officer && $officer->email) {
            $this->sendEmailNotification(
                $officer->email,
                __('Task Completed Alert: ') . $task->title,
                __('Employee ') . Auth::user()->name . __(' has marked the task "') . $task->title . __('" as completed. Notes: ') . "\n" . $request->completion_notes
            );
        }

        // Telegram Notification
        $telegramMsg = "✅ <b>Task Completed Submission</b>\n"
            . "👤 <b>Staff:</b> " . Auth::user()->name . "\n"
            . "📋 <b>Task:</b> {$task->title}\n"
            . "📝 <b>Notes:</b> " . \Illuminate\Support\Str::limit($request->completion_notes, 200) . "\n"
            . "📅 <b>Time:</b> " . now()->format('M d, Y h:i A');
        $this->sendTelegramNotification($telegramMsg);

        return $this->backWithSuccess(__('Task submitted for verification successfully.'));
    }

    /**
     * Standalone Financial Ledger View & Payout Requests
     */
    public function financialLedger()
    {
        $user = Auth::user();
        $staffDetail = $user->staffDetail;
        $payoutRequests = $user->staffPayoutRequests()->orderBy('created_at', 'desc')->get();
        $timeLogs = $user->staffTimeLogs()->whereNotNull('clocked_out_at')->orderBy('clocked_in_at', 'desc')->get();

        $totalEarned = $timeLogs->sum('earned_amount');
        $netOwed = $staffDetail->reimbursement + $staffDetail->bonus - $staffDetail->debt;
        $claimableAmount = $totalEarned + $netOwed;

        $title = __('Financial Ledger');

        return view('frontend.theme1.auth-staff.financial-ledger', compact(
            'title',
            'user',
            'staffDetail',
            'payoutRequests',
            'claimableAmount',
            'totalEarned',
            'netOwed'
        ));
    }

    /**
     * Submit payout request
     */
    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        
        StaffPayoutRequest::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Send email to Officer/Admin
        $officer = $user->staffDetail->officer ?: (User::role('admin')->first() ?: User::first());
        if ($officer && $officer->email) {
            $this->sendEmailNotification(
                $officer->email,
                __('Payout Request Submitted - ') . $user->name,
                __('Employee ') . $user->name . __(' has requested a payout of $') . number_format($request->amount, 2) . "\nNotes: " . $request->notes
            );
        }

        // Telegram Notification
        $telegramMsg = "💰 <b>Payout Request Submitted</b>\n"
            . "👤 <b>Staff:</b> {$user->name}\n"
            . "🆔 <b>Staff ID:</b> " . ($user->staffDetail ? $user->staffDetail->staff_id : 'N/A') . "\n"
            . "💵 <b>Amount:</b> $" . number_format($request->amount, 2) . "\n"
            . "📝 <b>Notes:</b> " . ($request->notes ? \Illuminate\Support\Str::limit($request->notes, 200) : __('No notes'));
        $this->sendTelegramNotification($telegramMsg);

        return redirect()->route('staff.financial-ledger')->with('success', __('Your payout request has been submitted to your supervisor.'));
    }

    /**
     * Poll messages for staff member
     */
    public function pollMessages(Request $request)
    {
        try {
            $user = Auth::user();
            $staffDetail = $user->staffDetail;
            $officer = $staffDetail->officer;
            if (!$officer) {
                $officer = User::role('admin')->first() ?: User::first();
            }

            $lastId = intval($request->get('last_id', 0));

            $newMessages = StaffMessage::where('staff_user_id', $user->id)
                ->where('officer_user_id', $officer->id)
                ->where('id', '>', $lastId)
                ->orderBy('id', 'asc')
                ->get();

            // Mark received messages as read
            StaffMessage::where('staff_user_id', $user->id)
                ->where('officer_user_id', $officer->id)
                ->where('sender_id', $officer->id)
                ->where('read', false)
                ->update(['read' => true]);

            $data = [];
            foreach ($newMessages as $msg) {
                $data[] = [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'is_sent' => ($msg->sender_id === $user->id),
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
     * Send message to Telegram Bot
     */
    protected function sendTelegramNotification($message)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (empty($token) || empty($chatId)) {
            return;
        }

        try {
            $url = "https://api.telegram.org/bot{$token}/sendMessage";
            $data = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ];

            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 5
                ]
            ];

            $context  = stream_context_create($options);
            @file_get_contents($url, false, $context);
        } catch (\Throwable $e) {
            // Fail silently
        }
    }

    /**
     * Staff request reimbursement for out of pocket expenses
     */
    public function requestReimbursement(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'entry_date' => 'required|date',
            'attachment' => 'nullable|file|mimes:pdf,jpeg,png,jpg,zip|max:10240',
        ]);

        try {
            $user = Auth::user();

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $filename = 'reimbursement_' . $user->id . '_' . time() . '_' . uniqid() . '.' . $request->attachment->getClientOriginalExtension();
                $request->attachment->move(public_path('upload/staff-ledger'), $filename);
                $attachmentPath = 'upload/staff-ledger/' . $filename;
            }

            $entry = \App\Models\StaffLedgerEntry::create([
                'user_id' => $user->id,
                'type' => 'reimbursement',
                'amount' => floatval($request->amount),
                'paid_amount' => 0.00,
                'status' => 'pending',
                'attachment_path' => $attachmentPath,
                'description' => $request->description,
                'entry_date' => $request->entry_date,
                'created_by' => 'staff',
            ]);

            // Send Email Notification to Officer/Admin
            $staffDetail = $user->staffDetail;
            $officer = $staffDetail ? $staffDetail->officer : null;
            if (!$officer) {
                $officer = User::role('admin')->first() ?: User::first();
            }

            if ($officer && $officer->email) {
                $this->sendEmailNotification(
                    $officer->email,
                    __('New Reimbursement Request Submitted - ') . $user->name,
                    __('Employee ') . $user->name . __(' has submitted a reimbursement request of $') . number_format($request->amount, 2) . "\n" .
                    __('Description: ') . $request->description . "\n" .
                    __('Please review and approve it in the admin panel.')
                );
            }

            // Telegram Notification
            $telegramMsg = "💸 <b>Reimbursement Request Submitted</b>\n"
                . "👤 <b>Staff:</b> {$user->name}\n"
                . "🆔 <b>Staff ID:</b> " . ($staffDetail ? $staffDetail->staff_id : 'N/A') . "\n"
                . "💵 <b>Amount:</b> $" . number_format($request->amount, 2) . "\n"
                . "📝 <b>Description:</b> {$request->description}\n"
                . "📅 <b>Date:</b> " . Carbon::parse($request->entry_date)->format('M d, Y');
            $this->sendTelegramNotification($telegramMsg);

            return redirect()->route('staff.financial-ledger')->with('success', __('Reimbursement request submitted successfully and is pending approval.'));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Download proof documents uploaded in the ledger
     */
    public function downloadLedgerProof($entryId)
    {
        try {
            $entry = \App\Models\StaffLedgerEntry::where('user_id', Auth::id())->findOrFail($entryId);
            
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
     * Generate printable Direct Deposit Form
     */
    public function generateDirectDepositForm()
    {
        return $this->viewDocument('direct_deposit');
    }

    /**
     * Staff Document Center
     */
    public function documentCenter()
    {
        try {
            $title = __('Staff Document Center');
            $templates = DocumentTemplate::where('type', 'staff')->where('status', true)->orderBy('title', 'asc')->get();

            return view('frontend.theme1.auth-staff.pages.documents.index', compact('title', 'templates'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    /**
     * Preview and print staff templates
     */
    public function viewDocument($key)
    {
        try {
            $user = Auth::user();
            $staffDetail = $user->staffDetail;
            $template = DocumentTemplate::where('type', 'staff')->where('key', $key)->where('status', true)->firstOrFail();

            $title = $template->title;
            $rawContent = $template->content;

            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

            // Replace staff templates placeholders
            $placeholders = [
                '{{employee_name}}' => $user->name,
                '{{employee_email}}' => $user->email,
                '{{employee_phone}}' => $user->phone ?: 'N/A',
                '{{employee_address}}' => $user->address ?: 'N/A',
                '{{staff_id}}' => $staffDetail ? $staffDetail->staff_id : 'N/A',
                '{{company_name}}' => $companyName,
                '{{date}}' => date('F d, Y'),
            ];

            $content = str_replace(array_keys($placeholders), array_values($placeholders), $rawContent);

            // Log document view
            \App\Models\DocumentLog::create([
                'template_key' => $key,
                'template_title' => $title,
                'staff_id' => $user->id,
                'recipient_email' => $user->email,
                'sent_by' => $user->id,
                'sent_to_email' => false,
                'status' => 'viewed',
                'tracking_token' => uniqid() . bin2hex(random_bytes(8)),
            ]);

            return view('frontend.theme1.auth-staff.pages.documents.print', compact('title', 'content', 'user', 'companyName'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    public function invoicesIndex()
    {
        try {
            $title = __('Client Invoices');
            $invoices = \App\Models\Invoice::with(['client', 'clientCase'])->orderBy('created_at', 'desc')->get();

            return view('frontend.theme1.auth-staff.pages.invoices.index', compact('title', 'invoices'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    public function invoiceShow($id)
    {
        try {
            $invoice = \App\Models\Invoice::with(['client', 'clientCase.attorney'])->findOrFail($id);

            $title = __('Invoice #') . $invoice->invoice_number;
            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

            $contactPage = \App\Models\PageSettings::where('name', 'contact')->first();
            $contactInfo = $contactPage ? $contactPage->sections()->where('name', 'contact_info')->first() : null;
            $emailInfo = $contactPage ? $contactPage->sections()->where('name', 'email')->first() : null;

            $companyAddress = env('COMPANY_ADDRESS') ?: ($contactInfo ? implode(', ', array_filter([$contactInfo->line_one, $contactInfo->line_two])) : '582 Professional Way, Financial District, DC');
            $companyPhone = env('COMPANY_PHONE') ?: ($contactInfo && $contactInfo->line_two && preg_match('/[0-9]/', $contactInfo->line_two) ? $contactInfo->line_two : '(216) 230-1837');
            $companyEmail = env('COMPANY_EMAIL') ?: ($emailInfo ? $emailInfo->line_one : 'support@yourcpaexpert.com');

            return view('frontend.theme1.auth-staff.pages.invoices.details', compact('title', 'invoice', 'companyName', 'companyAddress', 'companyPhone', 'companyEmail'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }
}
