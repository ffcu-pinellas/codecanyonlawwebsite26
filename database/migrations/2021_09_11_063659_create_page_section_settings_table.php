<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageSectionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *'number_of_content','bg_img','title','sub_title','description','fnt_img','show','form_title','form_subtitle','line_one','line_two','case_won','total_attorney','total_client','total_case_dismissed'
     * @return void
     */
    public function up()
    {
        Schema::create('page_section_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('page_id');
            $table->string('name')->unique();
            $table->integer('number_of_content')->nullable();
            $table->text('bg_img')->nullable();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->longText('description')->nullable();
            $table->text('fnt_img')->nullable();
            $table->boolean('show')->default(false);
            $table->string('form_title')->nullable();
            $table->string('form_subtitle')->nullable();
            $table->string('line_one')->nullable();
            $table->string('line_two')->nullable();
            $table->string('case_won')->nullable();
            $table->integer('total_attorney')->nullable();
            $table->integer('total_client')->nullable();
            $table->string('total_case_dismissed')->nullable();
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
        Schema::dropIfExists('page_section_settings');
    }
}
