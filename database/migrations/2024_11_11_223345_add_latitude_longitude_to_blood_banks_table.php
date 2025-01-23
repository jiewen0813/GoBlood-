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
        Schema::table('blood_bank_admins', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable(); // For latitude
            $table->decimal('longitude', 10, 7)->nullable(); // For longitude
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_bank_admins', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
