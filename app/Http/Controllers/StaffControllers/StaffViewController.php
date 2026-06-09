<?php

namespace App\Http\Controllers\StaffControllers;

use App\Http\Controllers\Controller;
use App\Models\StaffDetail;
use App\Models\StaffTimeLog;
use App\Models\StaffLoginLog;
use App\Models\StaffMessage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffViewController extends Controller
{
    public function __construct()
    {
        // Require auth for all methods except login page and login post
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

            // Check if user is staff
            if (!$user->hasRole('staff')) {
                Auth::logout();
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => __('This login portal is only for staff members.')]);
            }

            // Check if active
            $staffDetail = $user->staffDetail;
            if (!$staffDetail || !$staffDetail->is_active) {
                Auth::logout();
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => __('Your staff account is inactive. Please contact your administrator.')]);
            }

            // Log login event
            StaffLoginLog::create([
                'user_id' => $user->id,
                'logged_in_at' => now(),
                'ip_address' => $request->ip(),
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

        // Ensure staff detail exists
        if (!$staffDetail) {
            // Auto create detail if somehow missing
            $staffDetail = StaffDetail::create([
                'user_id' => $user->id,
                'staff_id' => 'STF-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'is_active' => true,
                'hired_at' => now(),
            ]);
        }

        // Check active clock-in log
        $activeLog = $user->staffTimeLogs()->whereNull('clocked_out_at')->first();

        // Calculate wage metrics
        $timeLogs = $user->staffTimeLogs()->whereNotNull('clocked_out_at')->orderBy('clocked_in_at', 'desc')->get();
        $totalDurationSeconds = $timeLogs->sum('duration_seconds');
        $totalEarned = $timeLogs->sum('earned_amount');

        // Total hours worked formatting
        $totalHoursWorked = round($totalDurationSeconds / 3600, 2);

        // Fallback assigned officer
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

        // Check if already clocked in
        $activeLog = $user->staffTimeLogs()->whereNull('clocked_out_at')->first();
        if ($activeLog) {
            return $this->backWithError(__('You are already clocked in.'));
        }

        // Create log
        StaffTimeLog::create([
            'user_id' => $user->id,
            'clocked_in_at' => now(),
            'hourly_rate_at_time' => $staffDetail->hourly_rate,
            'earned_amount' => 0.00,
        ]);

        return $this->backWithSuccess(__('Clocked in successfully.'));
    }

    /**
     * Clock Out Action
     */
    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $staffDetail = $user->staffDetail;

        // Find active log
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

        // If direct deposit documents are uploaded, we reset payment_verified status to false
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

        // Mark messages from officer to staff as read
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

        StaffMessage::create([
            'staff_user_id' => $user->id,
            'officer_user_id' => $officer->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'read' => false,
        ]);

        return $this->backWithSuccess(__('Message sent.'));
    }
}
