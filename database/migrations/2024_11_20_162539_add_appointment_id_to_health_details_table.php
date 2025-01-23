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
        Schema::table('health_details', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id')->nullable()->after('eventID'); // Add appointment_id column
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade'); // Add foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_details', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']); // Drop foreign key constraint
            $table->dropColumn('appointment_id'); // Remove appointment_id column
        });
    }
};
