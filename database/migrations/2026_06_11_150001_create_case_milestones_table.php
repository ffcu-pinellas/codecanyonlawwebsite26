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
        if (!Schema::hasTable('case_milestones')) {
            Schema::create('case_milestones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('case_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('status')->default('pending'); // pending, active, completed
                $table->dateTime('milestone_date')->nullable();
                $table->timestamps();

                $table->foreign('case_id')->references('id')->on('client_cases')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_milestones');
    }
};
