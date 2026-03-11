<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeaderFooterSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *"header","footer"
     * @return void
     */
    public function up()
    {
        Schema::create('header_footer_settings', function (Blueprint $table) {
            $table->id();
            $table->longText('header')->nullable();
            $table->longText('footer')->nullable();
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
        Schema::dropIfExists('header_footer_settings');
    }
}
