<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStaffSystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Update staff_details with pay_schedule
        Schema::table('staff_details', function (Blueprint $table) {
            if (!Schema::hasColumn('staff_details', 'pay_schedule')) {
                $table->string('pay_schedule')->default('bi-weekly')->after('payment_method');
            }
        });

        // 2. Update staff_login_logs with location
        Schema::table('staff_login_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('staff_login_logs', 'location')) {
                $table->string('location')->nullable()->after('ip_address');
            }
        });

        // 3. Create staff_tasks table
        Schema::create('staff_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_user_id')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, completed, approved
            $table->string('attachment_path')->nullable();
            $table->text('completion_notes')->nullable();
            $table->timestamps();

            $table->foreign('staff_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 4. Create staff_payout_requests table
        Schema::create('staff_payout_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, approved, paid
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_payout_requests');
        Schema::dropIfExists('staff_tasks');

        Schema::table('staff_login_logs', function (Blueprint $table) {
            if (Schema::hasColumn('staff_login_logs', 'location')) {
                $table->dropColumn('location');
            }
        });

        Schema::table('staff_details', function (Blueprint $table) {
            if (Schema::hasColumn('staff_details', 'pay_schedule')) {
                $table->dropColumn('pay_schedule');
            }
        });
    }
}
