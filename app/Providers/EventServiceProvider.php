<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            $user = $event->user;
            if ($user && $user->hasRole('client')) {
                $ip = request()->ip();
                $time = now()->format('Y-m-d H:i:s');
                $message = "🔑 *Client Login Notification*\n\n"
                         . "👤 *Name:* {$user->name}\n"
                         . "📧 *Email:* {$user->email}\n"
                         . "🌐 *IP Address:* {$ip}\n"
                         . "📅 *Time:* {$time}\n";
                
                \App\Models\GeneralSettings::sendTelegramNotification($message);
            }
        });

        Event::listen(\Illuminate\Auth\Events\Registered::class, function ($event) {
            $user = $event->user;
            if ($user && $user->hasRole('client')) {
                $ip = request()->ip();
                $time = now()->format('Y-m-d H:i:s');
                $message = "👤 *New Client Registered*\n\n"
                         . "👤 *Name:* {$user->name}\n"
                         . "📧 *Email:* {$user->email}\n"
                         . "🌐 *IP Address:* {$ip}\n"
                         . "📅 *Time:* {$time}\n";
                
                \App\Models\GeneralSettings::sendTelegramNotification($message);
            }
        });
    }
}
