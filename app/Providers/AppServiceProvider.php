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
        try {
            if (env('DB_USERNAME') != null) {
                if (Schema::hasTable('general_settings')) {
                    $generalSettings = \App\Models\GeneralSettings::first();
                    if ($generalSettings && $generalSettings?->site_name) {
                        \Illuminate\Support\Facades\Config::set('app.name', $generalSettings?->site_name);
                    }
                }

                // Ensure invoices table has all late fee & payment info columns
                if (Schema::hasTable('invoices')) {
                    $colsToAdd = [];
                    if (!Schema::hasColumn('invoices', 'late_fee_enabled')) $colsToAdd['late_fee_enabled'] = 'boolean';
                    if (!Schema::hasColumn('invoices', 'late_fee_type')) $colsToAdd['late_fee_type'] = 'string';
                    if (!Schema::hasColumn('invoices', 'late_fee_is_percentage')) $colsToAdd['late_fee_is_percentage'] = 'boolean';
                    if (!Schema::hasColumn('invoices', 'late_fee_amount')) $colsToAdd['late_fee_amount'] = 'decimal';
                    if (!Schema::hasColumn('invoices', 'late_fee_start_date')) $colsToAdd['late_fee_start_date'] = 'date';
                    if (!Schema::hasColumn('invoices', 'late_fee_accumulated')) $colsToAdd['late_fee_accumulated'] = 'decimal';
                    if (!Schema::hasColumn('invoices', 'payment_info')) $colsToAdd['payment_info'] = 'text';

                    if (!empty($colsToAdd)) {
                        Schema::table('invoices', function ($table) use ($colsToAdd) {
                            if (isset($colsToAdd['late_fee_enabled'])) $table->boolean('late_fee_enabled')->default(0)->nullable();
                            if (isset($colsToAdd['late_fee_type'])) $table->string('late_fee_type', 20)->default('daily')->nullable();
                            if (isset($colsToAdd['late_fee_is_percentage'])) $table->boolean('late_fee_is_percentage')->default(0)->nullable();
                            if (isset($colsToAdd['late_fee_amount'])) $table->decimal('late_fee_amount', 12, 2)->default(0.00)->nullable();
                            if (isset($colsToAdd['late_fee_start_date'])) $table->date('late_fee_start_date')->nullable();
                            if (isset($colsToAdd['late_fee_accumulated'])) $table->decimal('late_fee_accumulated', 12, 2)->default(0.00)->nullable();
                            if (isset($colsToAdd['payment_info'])) $table->text('payment_info')->nullable();
                        });
                    }
                }
            }
        } catch (\Throwable $th) {
            // Silence DB exceptions during CLI/testing boots
        }
    }
}
