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

        // 2. Extend messages table with Telegram message ID tracking and message column
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'message')) {
                $table->text('message')->nullable();
            }
            if (!Schema::hasColumn('messages', 'telegram_message_id')) {
                $table->string('telegram_message_id', 100)->nullable();
            }
            if (!Schema::hasColumn('messages', 'is_telegram_reply')) {
                $table->boolean('is_telegram_reply')->default(false);
            }
        });

        // 3. Extend staff_messages table with Telegram tracking and file attachments
        if (Schema::hasTable('staff_messages')) {
            Schema::table('staff_messages', function (Blueprint $table) {
                if (!Schema::hasColumn('staff_messages', 'telegram_message_id')) {
                    $table->string('telegram_message_id', 100)->nullable()->after('message');
                }
                if (!Schema::hasColumn('staff_messages', 'is_telegram_reply')) {
                    $table->boolean('is_telegram_reply')->default(false)->after('telegram_message_id');
                }
                if (!Schema::hasColumn('staff_messages', 'file')) {
                    $table->string('file', 255)->nullable()->after('is_telegram_reply');
                }
                if (!Schema::hasColumn('staff_messages', 'file_name')) {
                    $table->string('file_name', 255)->nullable()->after('file');
                }
            });
        }

        // 3.5 Ensure users table has lockout and pin attempt tracking columns
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'is_locked')) {
                    $table->boolean('is_locked')->default(false)->after('pin_hash');
                }
                if (!Schema::hasColumn('users', 'account_locked_until')) {
                    $table->timestamp('account_locked_until')->nullable()->after('is_locked');
                }
                if (!Schema::hasColumn('users', 'failed_pin_attempts')) {
                    $table->integer('failed_pin_attempts')->default(0)->after('account_locked_until');
                }
            });
        }

        // 4. Add composite indexes safely for high-throughput query performance
        $addIndexSafely = function($tableName, $columns, $indexName) {
            try {
                if (!Schema::hasTable($tableName)) return;
                $prefix = \Illuminate\Support\Facades\DB::getTablePrefix();
                $fullTable = $prefix . $tableName;
                $cols = implode('`, `', (array)$columns);
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `{$fullTable}` ADD INDEX `{$indexName}` (`{$cols}`)");
            } catch (\Throwable $e) {
                // Silently skip if index already exists or database dialect varies
            }
        };

        $addIndexSafely('client_cases', ['client_id', 'status'], 'idx_cases_client_status');
        $addIndexSafely('client_cases', ['created_at'], 'idx_cases_created');
        $addIndexSafely('invoices', ['user_id', 'status'], 'idx_inv_user_status');
        $addIndexSafely('staff_time_logs', ['user_id', 'clocked_in_at'], 'idx_staff_logs_user_in');
        $addIndexSafely('case_documents', ['case_id', 'user_id'], 'idx_case_docs_case_user');
        $addIndexSafely('system_audit_logs', ['action_key', 'created_at'], 'idx_audit_action_date');
        $addIndexSafely('messages', ['conversation_id', 'created_at'], 'idx_msg_convo_date');
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

