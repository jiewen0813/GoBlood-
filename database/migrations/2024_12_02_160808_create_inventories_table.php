<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_bank_id')->constrained('blood_bank_admins')->onDelete('cascade');
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']); // Enum for blood types
            $table->integer('quantity')->default(0); // Quantity of blood available
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}

