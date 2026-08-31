<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\LogoSettings;
use App\Models\SystemAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (!Auth::user()->hasRole('client')) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('client.dashboard');
            }
        }

        $logoFavicon = LogoSettings::first();
        $title = __('Staff & Attorney Portal Login');

        return view('auth.admin-login', compact('logoFavicon', 'title'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Verify that this user is not a regular client
            if ($user->hasRole('client') && !$user->hasAnyRole(['admin', 'attorney'])) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')->withErrors([
                    'email' => __('This portal is restricted to authorized Legal & CPA staff. Clients must sign in via the Client Portal at /login.')
                ]);
            }

            $request->session()->regenerate();

            SystemAuditLog::logAction('STAFF_LOGIN', "Staff member logged in via /admin/login.", $user->id, 'admin');

            return redirect()->intended(route('admin.dashboard'))->with('success', __('Welcome back, ') . $user->name);
        }

        return redirect()->route('admin.login')->withErrors([
            'email' => __('These credentials do not match our staff records.'),
        ])->withInput($request->only('email'));
    }
}
