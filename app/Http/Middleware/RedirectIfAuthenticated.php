<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if (!$user) {
                    return redirect()->route('login');
                }
                $roles = $user->roles ? $user->roles->pluck('name')->map(fn($r) => strtolower($r))->toArray() : [];

                if (in_array('admin', $roles) || in_array('attorney', $roles) || $user->id === 1) {
                    return redirect()->route('admin.dashboard');
                }

                if (in_array('staff', $roles)) {
                    return redirect()->route('staff.dashboard');
                }

                return redirect()->route('client.dashboard');
            }
        }

        return $next($request);
    }
}
