<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Custom Login Response ensuring direct role-based redirect
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            function () {
                return new class implements \Laravel\Fortify\Contracts\LoginResponse {
                    public function toResponse($request)
                    {
                        $user = auth()->user();
                        if ($user) {
                            $roles = $user->roles->pluck('name')->map(fn($r) => strtolower($r))->toArray();
                            if (in_array('client', $roles) && !in_array('admin', $roles)) {
                                session()->forget('url.intended');
                                return redirect()->route('client.dashboard');
                            }
                            if (in_array('staff', $roles) && !in_array('admin', $roles)) {
                                session()->forget('url.intended');
                                return redirect()->route('staff.dashboard');
                            }
                        }
                        return redirect()->intended(route('dashboard'));
                    }
                };
            }
        );

        $this->app->singleton(
            \Laravel\Fortify\Contracts\TwoFactorLoginResponse::class,
            function () {
                return new class implements \Laravel\Fortify\Contracts\TwoFactorLoginResponse {
                    public function toResponse($request)
                    {
                        $user = auth()->user();
                        if ($user) {
                            $roles = $user->roles->pluck('name')->map(fn($r) => strtolower($r))->toArray();
                            if (in_array('client', $roles) && !in_array('admin', $roles)) {
                                session()->forget('url.intended');
                                return redirect()->route('client.dashboard');
                            }
                            if (in_array('staff', $roles) && !in_array('admin', $roles)) {
                                session()->forget('url.intended');
                                return redirect()->route('staff.dashboard');
                            }
                        }
                        return redirect()->intended(route('dashboard'));
                    }
                };
            }
        );

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
