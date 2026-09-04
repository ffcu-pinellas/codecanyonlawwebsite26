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
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::check()) {
            $roles = Auth::user()->roles->pluck('name')->map(fn($r) => strtolower($r))->toArray();
            if (in_array('admin', $roles) || in_array('attorney', $roles) || in_array('staff', $roles)) {
                Auth::guard('admin')->login(Auth::user());
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('client.dashboard');
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
            $roles = $user->roles->pluck('name')->map(fn($r) => strtolower($r))->toArray();

            // Verify that this user is authorized staff/attorney/admin
            $isStaff = in_array('admin', $roles) || in_array('attorney', $roles) || in_array('staff', $roles) || $user->id === 1;

            if (!$isStaff) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')->withErrors([
                    'email' => __('This portal is restricted to authorized Legal & CPA staff. Clients must sign in via the Client Portal at /login.')
                ]);
            }

            // Authenticate on admin guard as well so admin session persists during client impersonation
            Auth::guard('admin')->login($user, $remember);

            $request->session()->regenerate();

            SystemAuditLog::logAction('STAFF_LOGIN', "Staff member logged in via /admin/login.", $user->id, 'admin');

            return redirect()->intended(route('admin.dashboard'))->with('success', __('Welcome back, ') . $user->name);
        }

        return redirect()->route('admin.login')->withErrors([
            'email' => __('These credentials do not match our staff records.'),
        ])->withInput($request->only('email'));
    }
}
