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
    Schema::table('users', function (Blueprint $table) {
        $table->string('ic_number')->unique();    
        $table->string('blood_type');   // Add blood type
        $table->string('phone');        // Add phone number
        $table->text('address');        // Add address
        $table->date('dob');            // Add date of birth
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['ic_number', 'blood_type', 'phone', 'address', 'dob']);
    });
}

};