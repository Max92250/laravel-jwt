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

        Schema::table('images', function (Blueprint $table) {
            // Add 'active' column as enum with default value of 1 (active)
            $table->enum('status', [0, 1])->default(1)->comment('0: Inactive, 1: Active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
