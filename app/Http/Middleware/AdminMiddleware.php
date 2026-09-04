<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        if (Auth::user()) {
            if (!Auth::user()->hasRole('client')) {
                return $next($request);
            }

            // If an admin/staff is currently in impersonation mode
            if (session()->has('impersonator_admin') || session()->has('impersonated_by')) {
                // If requesting exit impersonation, permit request to proceed to controller
                if ($request->routeIs('*stop-impersonation*') || $request->is('*stop-impersonation*')) {
                    return $next($request);
                }

                // If navigating to admin panel while impersonating, seamlessly restore original admin session
                $adminId = session('impersonator_admin.id') ?? session('impersonated_by');
                session()->forget(['impersonator_admin', 'impersonated_by', 'admin_login_as_bypass', 'session_locked']);
                Auth::loginUsingId($adminId);
                return $next($request);
            }
        }
        
        abort(403, 'Unauthorized action.');
    }
}
