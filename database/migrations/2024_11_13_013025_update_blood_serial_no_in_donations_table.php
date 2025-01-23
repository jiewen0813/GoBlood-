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
            // Change blood_serial_no to bigInteger and make it unique
            $table->bigInteger('blood_serial_no')->change();
        });

        // Set initial blood_serial_no for existing records if needed
        DB::statement('SET @serialNo = 100000000;');
        DB::statement('UPDATE donations SET blood_serial_no = (@serialNo := @serialNo + 1) WHERE blood_serial_no IS NULL;');
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
