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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade')->comment('The ID of the member associated with this shipping address');
            $table->string('name')->comment('name of the person who will receive package');
            $table->string('address_line1')->comment('First line of the shipping address');
            $table->string('address_line2')->nullable()->comment('Second line of the shipping address')->nullable();
            $table->string('city')->comment('City of the shipping address');
            $table->string('state')->comment('State of the shipping address');
            $table->string('postal_code')->comment('Postal code of the shipping address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
