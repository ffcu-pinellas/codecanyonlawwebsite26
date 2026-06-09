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
     * Helper to send raw email notifications
     */
    protected function sendEmailNotification($to, $subject, $body)
    {
        try {
            Mail::raw($body, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });
        } catch (\Throwable $e) {
            // Silent fallback if email servers are not configured
        }
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
            $staffDetail = StaffDetail::create([
                'user_id' => $user->id,
                'staff_id' => 'STF-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
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

        $title = __('Staff Dashboard');

        return view('frontend.theme1.auth-staff.dashboard', compact(
            'title',
            'user',
            'staffDetail',
            'activeLog',
            'timeLogs',
            'totalHoursWorked',
            'totalEarned',
            'officer'
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
            'void_check' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'direct_deposit_form' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $user = Auth::user();
        $staffDetail = $user->staffDetail;

        $updateData = [
            'payment_method' => $request->payment_method,
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

        if ($docUploaded || $staffDetail->payment_method !== $request->payment_method) {
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

        // Email Alert to Officer/Admin
        $officer = $user->staffDetail->officer ?: (User::role('admin')->first() ?: User::first());
        if ($officer && $officer->email) {
            $this->sendEmailNotification(
                $officer->email,
                __('Payout Request Submitted - ') . $user->name,
                __('Employee ') . $user->name . __(' has requested a payout of $') . number_format($request->amount, 2) . "\nNotes: " . $request->notes
            );
        }

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
}
