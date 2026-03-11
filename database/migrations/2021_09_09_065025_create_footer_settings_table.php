<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFooterSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *"top_footer_show","column1_short_disc","show_social","column2_recent_post_title","column2_recent_post_number","column3_popular_post_title","column3_popular_post_title_number","column4_title","column4_description","footer_logo", "bottom_footer_show","footer_copy_right"
     * @return void
     */
    public function up()
    {
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('show')->default(true);
            $table->string('column1_short_disc')->nullable();
            $table->boolean('show_social')->default(true);
            $table->string('column2_recent_post_title')->nullable();
            $table->string('column2_recent_post_number')->nullable();
            $table->string('column3_popular_post_title')->nullable();
            $table->string('column3_popular_post_title_number')->nullable();
            $table->string('column4_title')->nullable();
            $table->string('column4_description')->nullable();
            $table->string('footer_logo')->nullable();
            $table->boolean('bottom_footer_show')->default(true);
            $table->string('footer_copy_right')->nullable();
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
        Schema::dropIfExists('footer_settings');
    }
}
