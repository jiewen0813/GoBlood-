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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Reference to user
            $table->foreignId('blood_bank_id')->constrained('blood_bank_admins')->onDelete('cascade'); // Reference to blood bank
            $table->date('appointment_date');
            $table->time('time_slot'); // Use time format for 30-minute slots
            $table->enum('status', ['pending', 'approved', 'cancelled'])->default('pending');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
