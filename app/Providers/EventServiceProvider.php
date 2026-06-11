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
                $escapedName = htmlspecialchars($user->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedEmail = htmlspecialchars($user->email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                
                $message = "🔑 <b>Client Login Notification</b>\n\n"
                         . "👤 <b>Name:</b> {$escapedName}\n"
                         . "📧 <b>Email:</b> {$escapedEmail}\n"
                         . "🌐 <b>IP Address:</b> {$ip}\n"
                         . "📅 <b>Time:</b> {$time}\n";
                
                \App\Models\GeneralSettings::sendTelegramNotification($message);
            }
        });

        Event::listen(\Illuminate\Auth\Events\Registered::class, function ($event) {
            $user = $event->user;
            if ($user && $user->hasRole('client')) {
                $ip = request()->ip();
                $time = now()->format('Y-m-d H:i:s');
                $escapedName = htmlspecialchars($user->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedEmail = htmlspecialchars($user->email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                
                $message = "👤 <b>New Client Registered</b>\n\n"
                         . "👤 <b>Name:</b> {$escapedName}\n"
                         . "📧 <b>Email:</b> {$escapedEmail}\n"
                         . "🌐 <b>IP Address:</b> {$ip}\n"
                         . "📅 <b>Time:</b> {$time}\n";
                
                \App\Models\GeneralSettings::sendTelegramNotification($message);
            }
        });
    }
}
