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
        Schema::create('blood_donation_events', function (Blueprint $table) {
            $table->id('eventID');
            $table->string('eventName', 50);
            $table->date('eventDate');
            $table->string('eventLocation', 50);
            $table->string('eventPoster')->nullable(); // File path for the event poster
            $table->unsignedBigInteger('blood_bank_admin_id'); // Foreign key to blood bank admins

            // Foreign key constraint linking to the blood bank admins table
            $table->foreign('blood_bank_admin_id')->references('id')->on('blood_bank_admins')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('blood_donation_events');
    }
};
