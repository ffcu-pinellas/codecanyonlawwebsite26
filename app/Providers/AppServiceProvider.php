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

                // 1. Ensure users table has 2FA, PIN, & preference columns
                if (Schema::hasTable('users')) {
                    Schema::table('users', function ($table) {
                        if (!Schema::hasColumn('users', 'pin_hash')) $table->string('pin_hash', 255)->nullable()->after('password');
                        if (!Schema::hasColumn('users', 'is_temp_password')) $table->boolean('is_temp_password')->default(0)->after('pin_hash');
                        if (!Schema::hasColumn('users', 'is_first_login')) $table->boolean('is_first_login')->default(0)->after('is_temp_password');
                        if (!Schema::hasColumn('users', 'assigned_attorney_id')) $table->unsignedBigInteger('assigned_attorney_id')->nullable()->after('is_first_login');
                        if (!Schema::hasColumn('users', 'preferred_currency')) $table->string('preferred_currency', 10)->default('USD')->after('assigned_attorney_id');
                        if (!Schema::hasColumn('users', 'device_history')) $table->json('device_history')->nullable()->after('preferred_currency');
                        if (!Schema::hasColumn('users', 'two_factor_enabled')) $table->boolean('two_factor_enabled')->default(1)->after('password');
                        if (!Schema::hasColumn('users', 'otp_code')) $table->string('otp_code', 10)->nullable()->after('two_factor_enabled');
                        if (!Schema::hasColumn('users', 'otp_expires_at')) $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
                        if (!Schema::hasColumn('users', 'otp_method')) $table->string('otp_method', 10)->default('email')->after('otp_expires_at');
                    });
                }

                // 2. Ensure case_documents table has vault columns
                if (Schema::hasTable('case_documents')) {
                    Schema::table('case_documents', function ($table) {
                        if (!Schema::hasColumn('case_documents', 'document_type')) $table->string('document_type', 100)->nullable()->default('Standard / General Document')->after('is_client_uploaded');
                        if (!Schema::hasColumn('case_documents', 'requires_signature')) $table->boolean('requires_signature')->default(0)->after('document_type');
                        if (!Schema::hasColumn('case_documents', 'is_signed')) $table->boolean('is_signed')->default(0)->after('requires_signature');
                        if (!Schema::hasColumn('case_documents', 'signed_at')) $table->timestamp('signed_at')->nullable()->after('is_signed');
                        if (!Schema::hasColumn('case_documents', 'custom_content')) $table->longText('custom_content')->nullable()->after('signed_at');
                        if (!Schema::hasColumn('case_documents', 'visibility')) $table->string('visibility', 20)->nullable()->default('client_visible')->after('custom_content');
                    });
                }

                // 3. Ensure case_milestones table has visibility and color
                if (Schema::hasTable('case_milestones')) {
                    Schema::table('case_milestones', function ($table) {
                        if (!Schema::hasColumn('case_milestones', 'visibility')) $table->string('visibility', 20)->nullable()->default('client_visible')->after('status');
                        if (!Schema::hasColumn('case_milestones', 'color')) $table->string('color', 20)->nullable()->default('completed')->after('visibility');
                    });
                }

                // 4. Ensure invoices table has late fee & payment info columns
                if (Schema::hasTable('invoices')) {
                    Schema::table('invoices', function ($table) {
                        if (!Schema::hasColumn('invoices', 'late_fee_enabled')) $table->boolean('late_fee_enabled')->default(0)->nullable();
                        if (!Schema::hasColumn('invoices', 'late_fee_type')) $table->string('late_fee_type', 20)->default('daily')->nullable();
                        if (!Schema::hasColumn('invoices', 'late_fee_is_percentage')) $table->boolean('late_fee_is_percentage')->default(0)->nullable();
                        if (!Schema::hasColumn('invoices', 'late_fee_amount')) $table->decimal('late_fee_amount', 12, 2)->default(0.00)->nullable();
                        if (!Schema::hasColumn('invoices', 'late_fee_start_date')) $table->date('late_fee_start_date')->nullable();
                        if (!Schema::hasColumn('invoices', 'late_fee_accumulated')) $table->decimal('late_fee_accumulated', 12, 2)->default(0.00)->nullable();
                        if (!Schema::hasColumn('invoices', 'payment_info')) $table->text('payment_info')->nullable();
                    });
                }

                // 5. Ensure client_cases table has stage and schedule settings
                if (Schema::hasTable('client_cases')) {
                    Schema::table('client_cases', function ($table) {
                        if (!Schema::hasColumn('client_cases', 'lifecycle_stage')) $table->integer('lifecycle_stage')->default(1)->after('status');
                        if (!Schema::hasColumn('client_cases', 'progress_percent')) $table->integer('progress_percent')->default(20)->after('lifecycle_stage');
                        if (!Schema::hasColumn('client_cases', 'claim_amount')) $table->decimal('claim_amount', 15, 2)->default(0.00)->after('progress_percent');
                        if (!Schema::hasColumn('client_cases', 'settled_amount')) $table->decimal('settled_amount', 15, 2)->default(0.00)->after('claim_amount');
                        if (!Schema::hasColumn('client_cases', 'currency')) $table->string('currency', 10)->default('USD')->after('settled_amount');
                        if (!Schema::hasColumn('client_cases', 'show_financial_schedule')) $table->boolean('show_financial_schedule')->default(0)->after('currency');
                        if (!Schema::hasColumn('client_cases', 'show_settlement_escrow')) $table->boolean('show_settlement_escrow')->default(0)->after('show_financial_schedule');
                        if (!Schema::hasColumn('client_cases', 'show_jurisdiction_tracker')) $table->boolean('show_jurisdiction_tracker')->default(0)->after('show_settlement_escrow');
                        if (!Schema::hasColumn('client_cases', 'schedule_title')) $table->string('schedule_title', 150)->default('Audit & Financial Schedule')->after('show_jurisdiction_tracker');
                        if (!Schema::hasColumn('client_cases', 'settlement_title')) $table->string('settlement_title', 150)->default('Retainer & Trust Settlement Hub')->after('schedule_title');
                        if (!Schema::hasColumn('client_cases', 'jurisdiction_title')) $table->string('jurisdiction_title', 150)->default('Court & Regulatory Jurisdictions')->after('settlement_title');
                    });
                }
            }
        } catch (\Throwable $th) {
            // Silence DB exceptions during CLI/testing boots
        }
    }
}
