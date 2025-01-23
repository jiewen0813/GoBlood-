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
            $table->enum('status', ['Confirmed', 'Cancelled', 'Completed'])
                ->default('Confirmed')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Revert the status column back to its previous state
            $table->enum('status', ['Pending', 'Approved', 'Cancelled', 'Completed'])
                ->default('Pending')
                ->change();
        });
    }
};

