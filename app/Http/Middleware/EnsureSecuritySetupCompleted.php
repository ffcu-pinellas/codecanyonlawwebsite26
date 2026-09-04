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
            } elseif ($user->two_factor_enabled && !session('two_factor_verified')) {
                // Allow the verify-otp routes through without redirect loop
                $allow = ['client.security.verify-otp', 'client.security.verify-otp.send', 'client.security.verify-otp.resend', 'client.security.verify-otp.check', 'client.stop-impersonation', '*stop-impersonation*', 'logout'];
                $passes = false;
                foreach ($allow as $r) { if ($request->routeIs($r)) { $passes = true; break; } }
                if (!$passes) {
                    return redirect()->route('client.security.verify-otp');
                }
            }
        }

        return $next($request);
    }
}
