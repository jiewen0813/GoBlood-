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
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id(); 
            $table->string('request_type'); 
            $table->string('blood_type'); 
            $table->integer('quantity')->default(1); 
            $table->string('location'); 
            $table->string('phone'); 
            $table->text('notes')->nullable(); 
            $table->enum('status', ['Active', 'Completed', 'Cancelled'])->default('Active'); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
