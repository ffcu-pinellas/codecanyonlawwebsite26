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
        if (!Schema::hasTable('call_sessions')) {
            Schema::create('call_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('call_id', 64)->unique();
                $table->unsignedBigInteger('caller_id')->index();
                $table->unsignedBigInteger('recipient_id')->index();
                $table->string('caller_name', 255)->nullable();
                $table->string('caller_avatar', 255)->nullable();
                $table->string('recipient_name', 255)->nullable();
                $table->string('recipient_avatar', 255)->nullable();
                $table->string('call_type', 20)->default('audio'); // audio | video
                $table->string('status', 20)->default('ringing')->index(); // ringing, connected, ended, declined, missed, cancelled
                $table->longText('offer')->nullable();
                $table->longText('answer')->nullable();
                $table->longText('caller_candidates')->nullable();
                $table->longText('callee_candidates')->nullable();
                $table->string('conversation_slug', 100)->nullable()->index();
                $table->string('decline_reason', 255)->nullable();
                $table->unsignedBigInteger('connected_at')->nullable();
                $table->unsignedBigInteger('ended_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_sessions');
    }
};
