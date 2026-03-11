<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeaderSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *"show","left_content","right_content"
     * @return void
     */
    public function up()
    {
        Schema::create('header_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('show')->default(true);
            $table->string('left_content')->nullable();
            $table->string('right_content')->nullable();
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
        Schema::dropIfExists('header_settings');
    }
}
