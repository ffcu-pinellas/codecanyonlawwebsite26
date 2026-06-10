<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectEnhancementsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Client Cases table
        Schema::create('client_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('attorney_id')->nullable();
            $table->string('status')->default('active'); // pending, active, suspended, resolved
            $table->dateTime('court_date')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('attorney_id')->references('id')->on('users')->onDelete('set null');
        });

        // 2. Case Documents (Document Vault)
        Schema::create('case_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('user_id'); // uploader
            $table->string('title');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->boolean('is_client_uploaded')->default(false);
            $table->timestamps();

            $table->foreign('case_id')->references('id')->on('client_cases')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 3. Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('case_id')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->string('status')->default('unpaid'); // unpaid, paid, cancelled
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('case_id')->references('id')->on('client_cases')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 4. Activity Logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('case_documents');
        Schema::dropIfExists('client_cases');
    }
}
