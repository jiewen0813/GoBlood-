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
        Schema::table('redemptions', function (Blueprint $table) {
            $table->boolean('is_used')->default(false)->after('qr_code'); // Tracks if the reward is used
            $table->timestamp('used_at')->nullable()->after('is_used');  // When the reward was used
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('redemptions', function (Blueprint $table) {
            $table->dropColumn(['is_used', 'used_at']);
        });
    }
};
