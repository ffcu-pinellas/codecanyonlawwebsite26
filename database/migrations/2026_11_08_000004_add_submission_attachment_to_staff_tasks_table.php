<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('staff_tasks')) {
            Schema::table('staff_tasks', function (Blueprint $table) {
                if (!Schema::hasColumn('staff_tasks', 'submission_attachment_path')) {
                    $table->string('submission_attachment_path')->nullable()->after('attachment_path');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('staff_tasks')) {
            Schema::table('staff_tasks', function (Blueprint $table) {
                if (Schema::hasColumn('staff_tasks', 'submission_attachment_path')) {
                    $table->dropColumn('submission_attachment_path');
                }
            });
        }
    }
};
