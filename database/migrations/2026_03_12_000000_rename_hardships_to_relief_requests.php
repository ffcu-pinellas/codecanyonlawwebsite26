<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameHardshipsToReliefRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('hardships') && !Schema::hasTable('relief_requests')) {
            Schema::rename('hardships', 'relief_requests');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('relief_requests') && !Schema::hasTable('hardships')) {
            Schema::rename('relief_requests', 'hardships');
        }
    }
}
