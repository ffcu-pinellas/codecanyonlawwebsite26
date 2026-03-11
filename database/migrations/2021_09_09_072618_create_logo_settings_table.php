<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogoSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *"logo","favicon","site_tag_image"
     * @return void
     */
    public function up()
    {
        Schema::create('logo_settings', function (Blueprint $table) {
            $table->id();
            $table->text('logo')->nullable();
            $table->text('favicon')->nullable();
            $table->text('site_tag_image')->nullable();
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
        Schema::dropIfExists('logo_settings');
    }
}
