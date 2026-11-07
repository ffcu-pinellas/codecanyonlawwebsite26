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
                if (!Schema::hasColumn('case_documents', 'client_id')) {
                    $table->unsignedBigInteger('client_id')->nullable()->after('case_id');
                }
                if (!Schema::hasColumn('case_documents', 'document_title')) {
                    $table->string('document_title', 255)->nullable()->after('case_id');
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
                if (Schema::hasColumn('case_documents', 'client_id')) {
                    $table->dropColumn('client_id');
                }
                if (Schema::hasColumn('case_documents', 'document_title')) {
                    $table->dropColumn('document_title');
                }
            });
        }
    }
};
