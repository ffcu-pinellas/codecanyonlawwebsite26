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
            $table->string('document_type', 100)->nullable()->default('Standard / General Document')->after('is_client_uploaded');
            $table->boolean('requires_signature')->default(false)->after('document_type');
            $table->boolean('is_signed')->default(false)->after('requires_signature');
            $table->timestamp('signed_at')->nullable()->after('is_signed');
            $table->longText('custom_content')->nullable()->after('signed_at'); // for custom-created docs
            $table->string('visibility', 20)->nullable()->default('client_visible')->after('custom_content');
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
