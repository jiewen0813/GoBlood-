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
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('appointment_id')
                  ->nullable()  // Allow null values since not all donations are tied to appointments
                  ->constrained('appointments')  // References the 'appointments' table
                  ->onDelete('set null');  // If an appointment is deleted, set the appointment_id to null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            //
        });
    }
};
