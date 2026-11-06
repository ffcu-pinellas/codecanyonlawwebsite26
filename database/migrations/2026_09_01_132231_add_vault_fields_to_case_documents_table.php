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
        Schema::table('case_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('case_documents', 'document_type')) {
                $table->string('document_type', 100)->nullable()->default('Standard / General Document')->after('is_client_uploaded');
            }
            if (!Schema::hasColumn('case_documents', 'requires_signature')) {
                $table->boolean('requires_signature')->default(false)->after('document_type');
            }
            if (!Schema::hasColumn('case_documents', 'is_signed')) {
                $table->boolean('is_signed')->default(false)->after('requires_signature');
            }
            if (!Schema::hasColumn('case_documents', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('is_signed');
            }
            if (!Schema::hasColumn('case_documents', 'custom_content')) {
                $table->longText('custom_content')->nullable()->after('signed_at'); // for custom-created docs
            }
            if (!Schema::hasColumn('case_documents', 'visibility')) {
                $table->string('visibility', 20)->nullable()->default('client_visible')->after('custom_content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_documents', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'requires_signature', 'is_signed', 'signed_at', 'custom_content', 'visibility']);
        });
    }
};
