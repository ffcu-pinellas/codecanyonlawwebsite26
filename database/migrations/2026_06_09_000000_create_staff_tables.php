<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. staff_details table
        if (!Schema::hasTable('staff_details')) {
            Schema::create('staff_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('staff_id')->unique();
                $table->string('position')->nullable();
                $table->date('hired_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->decimal('hourly_rate', 10, 2)->default(0.00);
                $table->date('next_pay_date')->nullable();
                $table->decimal('bonus', 10, 2)->default(0.00);
                $table->decimal('debt', 10, 2)->default(0.00);
                $table->decimal('reimbursement', 10, 2)->default(0.00);
                $table->unsignedBigInteger('assigned_officer_id')->nullable()->index();
                $table->string('payment_method')->default('paycheck');
                $table->string('void_check_path')->nullable();
                $table->string('direct_deposit_form_path')->nullable();
                $table->boolean('payment_verified')->default(false);
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('assigned_officer_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // 2. staff_time_logs table
        if (!Schema::hasTable('staff_time_logs')) {
            Schema::create('staff_time_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->dateTime('clocked_in_at');
                $table->dateTime('clocked_out_at')->nullable();
                $table->integer('duration_seconds')->default(0);
                $table->decimal('hourly_rate_at_time', 10, 2)->default(0.00);
                $table->decimal('earned_amount', 10, 2)->default(0.00);
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // 3. staff_login_logs table
        if (!Schema::hasTable('staff_login_logs')) {
            Schema::create('staff_login_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->dateTime('logged_in_at');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // 4. staff_messages table
        if (!Schema::hasTable('staff_messages')) {
            Schema::create('staff_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('staff_user_id')->index();
                $table->unsignedBigInteger('officer_user_id')->index();
                $table->unsignedBigInteger('sender_id')->index();
                $table->text('message');
                $table->boolean('read')->default(false);
                $table->timestamps();

                $table->foreign('staff_user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('officer_user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Register Spatie Role 'staff'
        try {
            if (\Schema::hasTable('roles')) {
                \DB::table('roles')->insertOrIgnore([
                    'name' => 'staff',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_messages');
        Schema::dropIfExists('staff_login_logs');
        Schema::dropIfExists('staff_time_logs');
        Schema::dropIfExists('staff_details');
    }
}
