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
                $table->string('payment_method')->nullable();
                $table->string('payment_reference')->nullable();
                $table->string('payment_slip_path')->nullable();
                $table->text('payment_notes')->nullable();
                $table->dateTime('payment_submitted_at')->nullable();
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
                $table->dropColumn([
                    'payment_method',
                    'payment_reference',
                    'payment_slip_path',
                    'payment_notes',
                    'payment_submitted_at'
                ]);
            });
        }
    }
};
