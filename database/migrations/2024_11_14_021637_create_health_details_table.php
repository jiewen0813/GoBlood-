<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('health_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Reference to the user who submitted the form
            $table->unsignedBigInteger('eventID')->nullable(); // Reference to the specific event
            $table->json('responses'); // JSON column to store all answers from the form
            $table->string('source_type')->default('walk-in'); // Source of submission: 'appointment' or 'walk-in'
            $table->timestamps();

            // Define foreign key relationships
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('eventID')->references('eventID')->on('blood_donation_events')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_details');
    }
}
