<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSMTPSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *"mail_driver","mail_host","mail_port","mail_username","mail_password","mail_encryption","mail_from_address"
     * @return void
     */
    public function up()
    {
        Schema::create('s_m_t_p_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mail_driver');
            $table->string('mail_host');
            $table->string('mail_port');
            $table->string('mail_username');
            $table->string('mail_password');
            $table->string('mail_encryption');
            $table->string('mail_from_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('s_m_t_p_settings');
    }
}
