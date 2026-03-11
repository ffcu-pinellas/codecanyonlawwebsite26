<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
            if (env('DB_USERNAME') !=null) {
                if (Schema::hasTable('general_settings')) {
                    $generalSettings = \App\Models\GeneralSettings::first();
                    if ($generalSettings && $generalSettings?->site_name) {
                        \Illuminate\Support\Facades\Config::set('app.name', $generalSettings?->site_name);
                    }
                }
            }

    }
}
