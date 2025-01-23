<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key for donations
            $table->string('blood_serial_no')->unique(); // Blood serial number (unique)
            $table->date('date_donated'); // Date of donation
            $table->integer('quantity'); // Quantity of blood donated
            $table->unsignedBigInteger('blood_bank_id')->nullable(); // Foreign key to blood bank admins (nullable)
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->unsignedBigInteger('event_id'); // Foreign key to blood donation events table
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('blood_bank_id')->references('id')->on('blood_bank_admins')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('eventID')->on('blood_donation_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('donations');
    }
};
