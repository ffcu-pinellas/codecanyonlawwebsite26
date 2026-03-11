<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *'show', 'code', 'url'
     * @return void
     */
    public function up()
    {
        Schema::create('comment_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('show')->default(true);
            $table->longText('code')->nullable();
            $table->string('url')->nullable();
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
        Schema::dropIfExists('comment_settings');
    }
}
