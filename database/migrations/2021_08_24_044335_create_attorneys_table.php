<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttorneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attorneys', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('designation_id');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('fax');
            $table->string('image');
            $table->text('address');
            $table->text('description');
            $table->text('education');
            $table->text('professional_exp');
            $table->text('legal_exp');
            $table->boolean('status')->default(false)->comment('0 for Off, 1 for On');
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
        Schema::dropIfExists('attorneys');
    }
}
