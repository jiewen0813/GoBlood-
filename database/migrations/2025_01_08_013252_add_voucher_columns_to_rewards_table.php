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
        Schema::table('rewards', function (Blueprint $table) {
            $table->integer('voucher_limit')->default(0)->after('points_required')->comment('Maximum number of vouchers available');
            $table->integer('remaining_vouchers')->default(0)->after('voucher_limit')->comment('Currently available vouchers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropColumn(['voucher_limit', 'remaining_vouchers']);
        });
    }
};
