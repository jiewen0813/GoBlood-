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
        Schema::create('blood_bank_admins', function (Blueprint $table) {
            $table->id(); // Blood Bank ID
            $table->string('name'); // Blood Bank Name
            $table->string('username')->unique(); // Username for login
            $table->string('password'); // Password for login
            $table->string('contact')->nullable(); // Contact number
            $table->text('address')->nullable(); // Address
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_bank_admins');
    }
};
