<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->integer('service_id')->nullable();
            $table->string('service_title')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('problem_title');
            $table->text('problem_description');
            $table->string('solution_title');
            $table->text('solution_description');
            $table->string('result_title');
            $table->text('result_description');
            $table->string('featured_image');
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
        Schema::dropIfExists('case_studies');
    }
}
