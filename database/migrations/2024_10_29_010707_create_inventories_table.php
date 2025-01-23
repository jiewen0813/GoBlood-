<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id('inventoryID'); 
            $table->enum('bloodType', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->integer('quantity')->default(0);
            $table->date('dateUpdated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
