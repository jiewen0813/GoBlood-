<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('educations', function (Blueprint $table) {
            // Add foreign key constraint for created_by column
            $table->foreign('created_by')->references('id')->on('blood_bank_admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('educations', function (Blueprint $table) {
            // Drop the foreign key if rolling back
            $table->dropForeign(['created_by']);
        });
    }
}
