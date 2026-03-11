<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSEOSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *"meta_keyword","meta_description"
     * @return void
     */
    public function up()
    {
        Schema::create('s_e_o_settings', function (Blueprint $table) {
            $table->id();
            $table->longText('meta_keyword')->nullable();
            $table->longText('meta_description')->nullable();
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
        Schema::dropIfExists('s_e_o_settings');
    }
}
