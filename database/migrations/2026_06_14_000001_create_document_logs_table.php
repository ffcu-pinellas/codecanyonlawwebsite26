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
        if (!Schema::hasTable('document_logs')) {
            Schema::create('document_logs', function (Blueprint $table) {
                $table->id();
                $table->string('template_key');
                $table->string('template_title');
                $table->unsignedBigInteger('client_id')->nullable();
                $table->unsignedBigInteger('staff_id')->nullable();
                $table->string('recipient_email');
                $table->unsignedBigInteger('sent_by')->nullable();
                $table->boolean('sent_to_email')->default(false);
                $table->string('pdf_path')->nullable();
                $table->string('status')->default('sent'); // 'sent', 'opened'
                $table->string('tracking_token')->unique();
                $table->timestamp('opened_at')->nullable();
                $table->timestamps();

                $table->foreign('client_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('staff_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('sent_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_logs');
    }
};
