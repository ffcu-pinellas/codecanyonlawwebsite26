<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsToStaffDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('staff_details')) {
            Schema::table('staff_details', function (Blueprint $table) {
                if (!Schema::hasColumn('staff_details', 'check_name')) {
                    $table->string('check_name')->nullable();
                }
                if (!Schema::hasColumn('staff_details', 'check_address')) {
                    $table->text('check_address')->nullable();
                }
                if (!Schema::hasColumn('staff_details', 'bank_name')) {
                    $table->string('bank_name')->nullable();
                }
                if (!Schema::hasColumn('staff_details', 'account_name')) {
                    $table->string('account_name')->nullable();
                }
                if (!Schema::hasColumn('staff_details', 'account_number')) {
                    $table->string('account_number')->nullable();
                }
                if (!Schema::hasColumn('staff_details', 'routing_number')) {
                    $table->string('routing_number')->nullable();
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
        if (Schema::hasTable('staff_details')) {
            Schema::table('staff_details', function (Blueprint $table) {
                $table->dropColumn([
                    'check_name',
                    'check_address',
                    'bank_name',
                    'account_name',
                    'account_number',
                    'routing_number'
                ]);
            });
        }
    }
}
