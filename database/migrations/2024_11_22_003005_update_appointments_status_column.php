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
        Schema::table('appointments', function (Blueprint $table) {
            // Update the status column to include the new value 'Completed' with capitalized enum values
            $table->enum('status', ['Pending', 'Approved', 'Cancelled', 'Completed'])
                ->default('Pending')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Revert the status column back to the original values
            $table->enum('status', ['pending', 'approved', 'cancelled'])
                ->default('pending')
                ->change();
        });
    }
};
