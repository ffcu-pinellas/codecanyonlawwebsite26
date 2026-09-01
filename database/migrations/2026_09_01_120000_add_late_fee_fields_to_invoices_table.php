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
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'late_fee_enabled')) {
                    $table->boolean('late_fee_enabled')->default(0)->after('status');
                }
                if (!Schema::hasColumn('invoices', 'late_fee_type')) {
                    $table->string('late_fee_type', 20)->default('daily')->after('late_fee_enabled');
                }
                if (!Schema::hasColumn('invoices', 'late_fee_is_percentage')) {
                    $table->boolean('late_fee_is_percentage')->default(0)->after('late_fee_type');
                }
                if (!Schema::hasColumn('invoices', 'late_fee_amount')) {
                    $table->decimal('late_fee_amount', 12, 2)->default(0.00)->after('late_fee_is_percentage');
                }
                if (!Schema::hasColumn('invoices', 'late_fee_start_date')) {
                    $table->date('late_fee_start_date')->nullable()->after('late_fee_amount');
                }
                if (!Schema::hasColumn('invoices', 'late_fee_accumulated')) {
                    $table->decimal('late_fee_accumulated', 12, 2)->default(0.00)->after('late_fee_start_date');
                }
                if (!Schema::hasColumn('invoices', 'payment_info')) {
                    $table->text('payment_info')->nullable()->after('late_fee_accumulated');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                $columns = [
                    'late_fee_enabled',
                    'late_fee_type',
                    'late_fee_is_percentage',
                    'late_fee_amount',
                    'late_fee_start_date',
                    'late_fee_accumulated',
                    'payment_info',
                ];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('invoices', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
