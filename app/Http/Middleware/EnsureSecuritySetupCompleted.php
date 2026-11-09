<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSecuritySetupCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && !session()->has('impersonator_admin') && !session()->has('impersonated_by') && !session()->has('admin_login_as_bypass')) {
            if ($user->is_first_login) {
                if (!$request->routeIs('client.security.wizard*') && !$request->routeIs('*stop-impersonation*') && !$request->routeIs('logout')) {
                    return redirect()->route('client.security.wizard');
                }
            } elseif (($user->two_factor_enabled || $user->hasRole('staff')) && !session('two_factor_verified')) {
                // Allow the verify-otp routes and biometric passkey endpoints through without redirect loop
                $allow = [
                    'client.security.verify-otp',
                    'client.security.verify-otp.send',
                    'client.security.verify-otp.resend',
                    'client.security.verify-otp.check',
                    'client.security.2fa-gate',
                    'client.security.2fa-gate.verify',
                    'client.security.passkey.*',
                    'auth.passkey.*',
                    'client.security.quick-register-passkey',
                    'client.security.biometric-unlock-session',
                    'client.security.unlock-session',
                    'client.security.lock-session',
                    'client.security.check-session',
                    'client.stop-impersonation',
                    '*stop-impersonation*',
                    'staff.login',
                    'login',
                    'logout'
                ];
                $passes = false;
                foreach ($allow as $r) { if ($request->routeIs($r)) { $passes = true; break; } }
                if (!$passes) {
                    return redirect()->route('client.security.2fa-gate');
                }
            }
        }

        return $next($request);
    }
}
