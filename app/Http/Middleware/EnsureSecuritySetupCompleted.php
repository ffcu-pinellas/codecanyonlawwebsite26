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

        if ($user && $user->is_first_login) {
            // Allow the security wizard setup and logout routes
            if (!$request->routeIs('client.security.wizard*') && !$request->routeIs('logout')) {
                return redirect()->route('client.security.wizard');
            }
        }

        return $next($request);
    }
}
