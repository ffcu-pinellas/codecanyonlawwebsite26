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
        if (Schema::hasTable('document_logs')) {
            Schema::table('document_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('document_logs', 'action_required')) {
                    $table->string('action_required')->default('none')->after('status');
                }
                if (!Schema::hasColumn('document_logs', 'signed_path')) {
                    $table->string('signed_path')->nullable()->after('action_required');
                }
                if (!Schema::hasColumn('document_logs', 'admin_notes')) {
                    $table->text('admin_notes')->nullable()->after('signed_path');
                }
                if (!Schema::hasColumn('document_logs', 'recipient_notes')) {
                    $table->text('recipient_notes')->nullable()->after('admin_notes');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('document_logs')) {
            Schema::table('document_logs', function (Blueprint $table) {
                if (Schema::hasColumn('document_logs', 'action_required')) {
                    $table->dropColumn('action_required');
                }
                if (Schema::hasColumn('document_logs', 'signed_path')) {
                    $table->dropColumn('signed_path');
                }
                if (Schema::hasColumn('document_logs', 'admin_notes')) {
                    $table->dropColumn('admin_notes');
                }
                if (Schema::hasColumn('document_logs', 'recipient_notes')) {
                    $table->dropColumn('recipient_notes');
                }
            });
        }
    }
};
