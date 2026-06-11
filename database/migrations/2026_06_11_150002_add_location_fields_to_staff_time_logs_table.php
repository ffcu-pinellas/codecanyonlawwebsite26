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
        if (Schema::hasTable('staff_time_logs')) {
            Schema::table('staff_time_logs', function (Blueprint $table) {
                $table->string('clock_in_ip')->nullable();
                $table->string('clock_out_ip')->nullable();
                $table->decimal('clock_in_latitude', 10, 8)->nullable();
                $table->decimal('clock_in_longitude', 11, 8)->nullable();
                $table->decimal('clock_out_latitude', 10, 8)->nullable();
                $table->decimal('clock_out_longitude', 11, 8)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('staff_time_logs')) {
            Schema::table('staff_time_logs', function (Blueprint $table) {
                $table->dropColumn([
                    'clock_in_ip',
                    'clock_out_ip',
                    'clock_in_latitude',
                    'clock_in_longitude',
                    'clock_out_latitude',
                    'clock_out_longitude'
                ]);
            });
        }
    }
};
