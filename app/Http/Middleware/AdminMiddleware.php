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
        // 1. If admin is logged in under admin guard, allow immediately
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // 2. If logged in under default web guard
        if (Auth::check()) {
            $user = Auth::user();
            $roles = $user->roles->pluck('name')->map(fn($r) => strtolower($r))->toArray();

            // If user has admin/attorney/staff role or is user #1, grant access and sync admin guard
            if (in_array('admin', $roles) || in_array('attorney', $roles) || in_array('staff', $roles) || $user->id === 1) {
                Auth::guard('admin')->login($user);
                return $next($request);
            }

            // 3. If currently in an impersonation session
            if (session()->has('impersonator_admin') || session()->has('impersonated_by')) {
                // If requesting exit impersonation, allow request to pass to controller
                if ($request->routeIs('*stop-impersonation*') || $request->is('*stop-impersonation*')) {
                    return $next($request);
                }

                // If navigating to admin panel, restore original admin
                $adminId = session('impersonator_admin.id') ?? session('impersonated_by');
                if ($adminId) {
                    $adminUser = \App\Models\User::find($adminId);
                    if ($adminUser) {
                        session()->forget(['impersonator_admin', 'impersonated_by', 'admin_login_as_bypass', 'session_locked']);
                        Auth::login($adminUser);
                        Auth::guard('admin')->login($adminUser);
                        return $next($request);
                    }
                }
            }
        }
        
        abort(403, 'Unauthorized action. Authorized Legal & CPA staff only.');
    }
}
