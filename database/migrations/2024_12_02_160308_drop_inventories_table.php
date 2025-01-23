<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropInventoriesTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('inventories'); // Drops the inventories table
    }

    public function down()
    {
        // Optionally, you can reverse this migration if you need to recreate the table later.
    }
}
