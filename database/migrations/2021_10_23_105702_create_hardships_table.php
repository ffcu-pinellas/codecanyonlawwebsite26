<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHardshipsTable extends Migration
{
    /**
     * Run the migrations.
     *'user_id', 'name', 'phone', 'email', 'address', 'file_name', 'file', 'reason', 'details', 'offer'
     * @return void
     */
    public function up()
    {
        Schema::create('hardships', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('name');
            $table->string('phone',20);
            $table->string('email');
            $table->longText('address');
            $table->string('file_name')->nullable();
            $table->longText('file')->nullable();
            $table->longText('reason');
            $table->longText('details')->nullable();
            $table->longText('offer');
            $table->boolean('viewed')->default(false);
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
        Schema::dropIfExists('hardships');
    }
}
