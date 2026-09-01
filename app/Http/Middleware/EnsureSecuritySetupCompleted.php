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

        if ($user && !session()->has('impersonator_admin')) {
            if ($user->is_first_login) {
                if (!$request->routeIs('client.security.wizard*') && !$request->routeIs('client.stop-impersonation') && !$request->routeIs('logout')) {
                    return redirect()->route('client.security.wizard');
                }
            } elseif ($user->two_factor_enabled && !session('two_factor_verified')) {
                if (!$request->routeIs('client.security.2fa-gate*') && !$request->routeIs('client.stop-impersonation') && !$request->routeIs('logout')) {
                    return redirect()->route('client.security.2fa-gate');
                }
            }
        }

        return $next($request);
    }
}
