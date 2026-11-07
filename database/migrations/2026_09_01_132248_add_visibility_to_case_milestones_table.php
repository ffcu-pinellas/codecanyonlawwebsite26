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
        if (Schema::hasTable('case_milestones')) {
            Schema::table('case_milestones', function (Blueprint $table) {
                if (!Schema::hasColumn('case_milestones', 'visibility')) {
                    $table->string('visibility', 20)->nullable()->default('client_visible')->after('status');
                }
                if (!Schema::hasColumn('case_milestones', 'color')) {
                    $table->string('color', 20)->nullable()->default('completed')->after('visibility'); // completed|active|pending = green|yellow|blue
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_milestones', function (Blueprint $table) {
            $table->dropColumn(['visibility', 'color']);
        });
    }
};
