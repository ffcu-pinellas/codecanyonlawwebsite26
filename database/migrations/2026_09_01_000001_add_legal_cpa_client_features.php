<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Extend Users table with PIN, First-Login, Attorney assignment, and currency
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pin_hash')) {
                $table->string('pin_hash', 255)->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'is_temp_password')) {
                $table->boolean('is_temp_password')->default(false)->after('pin_hash');
            }
            if (!Schema::hasColumn('users', 'is_first_login')) {
                $table->boolean('is_first_login')->default(false)->after('is_temp_password');
            }
            if (!Schema::hasColumn('users', 'assigned_attorney_id')) {
                $table->unsignedBigInteger('assigned_attorney_id')->nullable()->after('is_first_login');
            }
            if (!Schema::hasColumn('users', 'preferred_currency')) {
                $table->string('preferred_currency', 10)->default('USD')->after('assigned_attorney_id');
            }
            if (!Schema::hasColumn('users', 'device_history')) {
                $table->json('device_history')->nullable()->after('preferred_currency');
            }
        });

        // 2. Extend Client Cases table with Legal & CPA customizable modules
        Schema::table('client_cases', function (Blueprint $table) {
            if (!Schema::hasColumn('client_cases', 'lifecycle_stage')) {
                $table->integer('lifecycle_stage')->default(1)->after('status');
            }
            if (!Schema::hasColumn('client_cases', 'progress_percent')) {
                $table->integer('progress_percent')->default(20)->after('lifecycle_stage');
            }
            if (!Schema::hasColumn('client_cases', 'claim_amount')) {
                $table->decimal('claim_amount', 15, 2)->default(0.00)->after('progress_percent');
            }
            if (!Schema::hasColumn('client_cases', 'settled_amount')) {
                $table->decimal('settled_amount', 15, 2)->default(0.00)->after('claim_amount');
            }
            if (!Schema::hasColumn('client_cases', 'currency')) {
                $table->string('currency', 10)->default('USD')->after('settled_amount');
            }
            if (!Schema::hasColumn('client_cases', 'show_financial_schedule')) {
                $table->boolean('show_financial_schedule')->default(false)->after('currency');
            }
            if (!Schema::hasColumn('client_cases', 'show_settlement_escrow')) {
                $table->boolean('show_settlement_escrow')->default(false)->after('show_financial_schedule');
            }
            if (!Schema::hasColumn('client_cases', 'show_jurisdiction_tracker')) {
                $table->boolean('show_jurisdiction_tracker')->default(false)->after('show_settlement_escrow');
            }
            if (!Schema::hasColumn('client_cases', 'schedule_title')) {
                $table->string('schedule_title', 150)->default('Audit & Financial Schedule')->after('show_jurisdiction_tracker');
            }
            if (!Schema::hasColumn('client_cases', 'settlement_title')) {
                $table->string('settlement_title', 150)->default('Retainer & Trust Settlement Hub')->after('schedule_title');
            }
            if (!Schema::hasColumn('client_cases', 'jurisdiction_title')) {
                $table->string('jurisdiction_title', 150)->default('Court & Regulatory Jurisdictions')->after('settlement_title');
            }
        });

        // 3. Create Case Financial / Audit Schedules Table
        if (!Schema::hasTable('case_financial_schedules')) {
            Schema::create('case_financial_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('case_id');
                $table->string('item_category', 100)->default('Asset / Schedule'); // e.g. 'Tax Filing', 'Audit Schedule', 'Retainer Deposit', 'Asset Ledger'
                $table->string('item_description', 255);
                $table->string('reference_code', 100)->nullable();
                $table->decimal('amount', 15, 2)->default(0.00);
                $table->string('currency', 10)->default('USD');
                $table->string('status', 50)->default('Audited'); // 'Pending Review', 'Audited', 'Reconciled', 'Disputed'
                $table->date('entry_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('case_id')->references('id')->on('client_cases')->onDelete('cascade');
            });
        }

        // 4. Create Case Settlements & Retainer Trust Table
        if (!Schema::hasTable('case_settlements')) {
            Schema::create('case_settlements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('case_id')->unique();
                $table->unsignedBigInteger('client_id');
                $table->decimal('gross_amount', 15, 2)->default(0.00);
                $table->decimal('legal_fee_percent', 5, 2)->default(10.00);
                $table->decimal('legal_fee_amount', 15, 2)->default(0.00);
                $table->decimal('expenses_amount', 15, 2)->default(0.00);
                $table->decimal('net_client_payout', 15, 2)->default(0.00);
                $table->string('currency', 10)->default('USD');
                $table->string('escrow_trust_ref', 100)->nullable();
                $table->string('custody_depository', 255)->default('IOLTA Legal Trust Account');
                $table->integer('clearance_stage')->default(1); // 1: Verified, 2: Escrow Cleared, 3: Settlement Approved, 4: Disbursed
                $table->string('status', 100)->default('Held in Trust');
                $table->string('payout_method', 100)->nullable();
                $table->text('payout_destination_details')->nullable();
                $table->timestamp('client_confirmed_at')->nullable();
                $table->string('client_signature_hash', 255)->nullable();
                $table->boolean('is_enabled')->default(true);
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('case_id')->references('id')->on('client_cases')->onDelete('cascade');
                $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // 5. Create Case Court & Regulatory Jurisdictions Table
        if (!Schema::hasTable('case_jurisdictions')) {
            Schema::create('case_jurisdictions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('case_id');
                $table->string('jurisdiction_name', 150); // e.g. "State Court of New York", "IRS Tax Tribunal", "Federal District Court"
                $table->string('court_venue', 255)->nullable();
                $table->string('action_type', 150); // e.g. "Civil Litigation", "Tax Audit Defense", "Regulatory Injunction", "Arbitration"
                $table->string('docket_number', 100)->nullable();
                $table->string('status', 100)->default('Filing Active'); // "Filing Active", "Docket Submitted", "Hearing Scheduled", "Resolved"
                $table->date('filing_date')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('is_enabled')->default(true);
                $table->timestamps();

                $table->foreign('case_id')->references('id')->on('client_cases')->onDelete('cascade');
            });
        }

        // 6. Create Legal & CPA KYC / Due Diligence Documents Table
        if (!Schema::hasTable('client_kyc_documents')) {
            Schema::create('client_kyc_documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id');
                $table->unsignedBigInteger('case_id')->nullable();
                $table->string('document_type', 100); // e.g. "Government ID", "Tax Return (W-2/1099)", "Corporate Articles", "Bank/Financial Statement", "Other"
                $table->string('file_title', 255);
                $table->string('file_path', 500);
                $table->string('file_size', 50)->nullable();
                $table->enum('status', ['Pending Review', 'Approved', 'Needs Resubmission'])->default('Pending Review');
                $table->text('reviewer_notes')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();

                $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('case_id')->references('id')->on('client_cases')->onDelete('set null');
            });
        }

        // 7. Create System Audit Logs Table
        if (!Schema::hasTable('system_audit_logs')) {
            Schema::create('system_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_type', 50)->default('client'); // 'admin', 'attorney', 'staff', 'client'
                $table->string('action_key', 100); // 'GENERATE_CREDENTIALS', 'SEND_WELCOME_EMAIL', 'IMPERSONATE_CLIENT', 'PIN_UPDATE', 'SETTLEMENT_CONFIRM', 'LOGIN_SUCCESS'
                $table->text('details')->nullable();
                $table->string('ip_address', 100)->nullable();
                $table->string('user_agent', 500)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_audit_logs');
        Schema::dropIfExists('client_kyc_documents');
        Schema::dropIfExists('case_jurisdictions');
        Schema::dropIfExists('case_settlements');
        Schema::dropIfExists('case_financial_schedules');

        Schema::table('client_cases', function (Blueprint $table) {
            $table->dropColumn([
                'lifecycle_stage', 'progress_percent', 'claim_amount', 'settled_amount',
                'currency', 'show_financial_schedule', 'show_settlement_escrow',
                'show_jurisdiction_tracker', 'schedule_title', 'settlement_title', 'jurisdiction_title'
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'pin_hash', 'is_temp_password', 'is_first_login',
                'assigned_attorney_id', 'preferred_currency', 'device_history'
            ]);
        });
    }
};
