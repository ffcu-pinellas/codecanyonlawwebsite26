<?php

namespace App\Http\Controllers\ClientViewControllers;

use App\Http\Controllers\Controller;
use App\Models\SystemAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientSecurityController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show First-Login Security Setup Wizard
     */
    public function showSecurityWizard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // If user already completed setup, send to dashboard
        if (!$user->is_first_login) {
            return redirect()->route('client.dashboard');
        }

        return view('frontend.theme1.client.security-wizard', [
            'title' => __('Account Security Setup | First-Time Activation'),
            'user' => $user,
        ]);
    }

    /**
     * Process First-Login Security Setup Wizard
     */
    public function processSecurityWizard(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'pin' => 'required|numeric|digits:4',
            'phone' => 'nullable|string|max:50',
            'preferred_currency' => 'nullable|string|in:USD,EUR,GBP,CAD,AUD',
        ], [
            'password.confirmed' => __('Password confirmation does not match.'),
            'password.min' => __('Password must be at least 8 characters long.'),
            'pin.digits' => __('Security PIN must be exactly 4 digits.'),
            'pin.numeric' => __('Security PIN must contain only numbers.'),
        ]);

        $user = Auth::user();

        $user->password = Hash::make($request->password);
        $user->pin_hash = Hash::make($request->pin);
        $user->is_temp_password = false;
        $user->is_first_login = false;
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->filled('preferred_currency')) {
            $user->preferred_currency = $request->preferred_currency;
        }
        $user->save();

        SystemAuditLog::logAction('SECURITY_WIZARD_COMPLETED', 'Client completed first-login password update and 4-digit PIN setup.', $user->id, 'client');

        return redirect()->route('client.dashboard')->with('success', __('Your permanent password and 4-digit Security PIN have been set successfully. Welcome to your Client Portal!'));
    }

    /**
     * Update 4-Digit Security PIN from Client Profile/Security
     */
    public function setPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:4|confirmed',
            'current_password' => 'required|string',
        ], [
            'pin.digits' => __('Security PIN must be exactly 4 digits.'),
            'pin.numeric' => __('Security PIN must contain only numbers.'),
            'pin.confirmed' => __('PIN confirmation does not match.'),
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', __('Current password does not match our records.'));
        }

        $user->pin_hash = Hash::make($request->pin);
        $user->save();

        SystemAuditLog::logAction('PIN_UPDATED', 'Client updated their 4-digit Security PIN.', $user->id, 'client');

        return redirect()->back()->with('success', __('Your 4-digit Security PIN has been updated successfully.'));
    }

    /**
     * AJAX Challenge verification for sensitive client actions
     */
    public function verifyPinChallenge(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
            'action_type' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$user->pin_hash) {
            // If user has no PIN yet, default allow or prompt
            return response()->json(['status' => 'success', 'message' => 'Verified']);
        }

        if (Hash::check($request->pin, $user->pin_hash)) {
            SystemAuditLog::logAction('PIN_CHALLENGE_SUCCESS', 'Authorized action: ' . $request->action_type, $user->id, 'client');
            return response()->json(['status' => 'success', 'message' => 'PIN verified successfully.']);
        }

        SystemAuditLog::logAction('PIN_CHALLENGE_FAILED', 'Failed PIN attempt for action: ' . $request->action_type, $user->id, 'client');
        return response()->json(['status' => 'error', 'message' => __('Invalid 4-digit PIN. Please try again.')], 422);
    }
}
