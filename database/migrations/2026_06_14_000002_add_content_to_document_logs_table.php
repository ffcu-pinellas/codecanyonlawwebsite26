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
                if (!Schema::hasColumn('document_logs', 'content')) {
                    $table->longText('content')->nullable()->after('template_title');
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
                if (Schema::hasColumn('document_logs', 'content')) {
                    $table->dropColumn('content');
                }
            });
        }
    }
};
