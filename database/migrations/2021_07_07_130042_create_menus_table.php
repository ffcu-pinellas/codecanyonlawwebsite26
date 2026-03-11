<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *'category_id', 'name', 'icon', 'url', 'target', 'Tooltip', 'parent_id', 'position'
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->integer('parent_id')->nullable()->comment('null = Main menu');
            $table->integer('position');
            $table->string('text');
            $table->string('icon');
            $table->longText('href');
            $table->string('target');
            $table->string('title')->nullable();
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
        Schema::dropIfExists('menus');
    }
}
