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
        // 1. Extend general_settings table with contact & telegram fields
        Schema::table('general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('general_settings', 'company_phone')) {
                $table->string('company_phone', 100)->nullable()->after('site_name');
            }
            if (!Schema::hasColumn('general_settings', 'company_email')) {
                $table->string('company_email', 150)->nullable()->after('company_phone');
            }
            if (!Schema::hasColumn('general_settings', 'company_address')) {
                $table->string('company_address', 255)->nullable()->after('company_email');
            }
            if (!Schema::hasColumn('general_settings', 'whatsapp_number')) {
                $table->string('whatsapp_number', 100)->nullable()->after('company_address');
            }
            if (!Schema::hasColumn('general_settings', 'telegram_reply_enabled')) {
                $table->boolean('telegram_reply_enabled')->default(true)->after('whatsapp_number');
            }
        });

        // 2. Extend messages table with Telegram message ID tracking
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'telegram_message_id')) {
                $table->string('telegram_message_id', 100)->nullable()->after('message');
            }
            if (!Schema::hasColumn('messages', 'is_telegram_reply')) {
                $table->boolean('is_telegram_reply')->default(false)->after('telegram_message_id');
            }
        });

        // 3. Extend staff_messages table with Telegram tracking
        if (Schema::hasTable('staff_messages')) {
            Schema::table('staff_messages', function (Blueprint $table) {
                if (!Schema::hasColumn('staff_messages', 'telegram_message_id')) {
                    $table->string('telegram_message_id', 100)->nullable()->after('message');
                }
                if (!Schema::hasColumn('staff_messages', 'is_telegram_reply')) {
                    $table->boolean('is_telegram_reply')->default(false)->after('telegram_message_id');
                }
            });
        }

        // 4. Add composite indexes for high-throughput query performance
        Schema::table('client_cases', function (Blueprint $table) {
            try {
                $table->index(['client_id', 'status'], 'idx_cases_client_status');
            } catch (\Throwable $e) {}
            try {
                $table->index(['created_at'], 'idx_cases_created');
            } catch (\Throwable $e) {}
        });

        Schema::table('invoices', function (Blueprint $table) {
            try {
                $table->index(['user_id', 'status'], 'idx_inv_user_status');
            } catch (\Throwable $e) {}
        });

        if (Schema::hasTable('staff_time_logs')) {
            Schema::table('staff_time_logs', function (Blueprint $table) {
                try {
                    $table->index(['user_id', 'clocked_in_at'], 'idx_staff_logs_user_in');
                } catch (\Throwable $e) {}
            });
        }

        if (Schema::hasTable('case_documents')) {
            Schema::table('case_documents', function (Blueprint $table) {
                try {
                    $table->index(['case_id', 'user_id'], 'idx_case_docs_case_user');
                } catch (\Throwable $e) {}
            });
        }

        if (Schema::hasTable('system_audit_logs')) {
            Schema::table('system_audit_logs', function (Blueprint $table) {
                try {
                    $table->index(['action_key', 'created_at'], 'idx_audit_action_date');
                } catch (\Throwable $e) {}
            });
        }

        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                try {
                    $table->index(['conversation_id', 'created_at'], 'idx_msg_convo_date');
                } catch (\Throwable $e) {}
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn(['company_phone', 'company_email', 'company_address', 'whatsapp_number', 'telegram_reply_enabled']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['telegram_message_id', 'is_telegram_reply']);
        });

        if (Schema::hasTable('staff_messages')) {
            Schema::table('staff_messages', function (Blueprint $table) {
                $table->dropColumn(['telegram_message_id', 'is_telegram_reply']);
            });
        }
    }
};
