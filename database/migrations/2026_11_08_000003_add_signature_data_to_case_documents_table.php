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
        if (Schema::hasTable('case_documents')) {
            Schema::table('case_documents', function (Blueprint $table) {
                if (!Schema::hasColumn('case_documents', 'signature_data')) {
                    $table->longText('signature_data')->nullable()->after('signed_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('case_documents')) {
            Schema::table('case_documents', function (Blueprint $table) {
                if (Schema::hasColumn('case_documents', 'signature_data')) {
                    $table->dropColumn('signature_data');
                }
            });
        }
    }
};
