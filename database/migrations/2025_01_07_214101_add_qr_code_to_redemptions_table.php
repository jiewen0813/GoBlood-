<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQrCodeToRedemptionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('redemptions', function (Blueprint $table) {
            $table->text('qr_code')->nullable()->after('points_used'); // Add the QR code column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('redemptions', function (Blueprint $table) {
            $table->dropColumn('qr_code');
        });
    }
}
