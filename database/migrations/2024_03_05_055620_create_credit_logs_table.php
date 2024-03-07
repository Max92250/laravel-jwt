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
        Schema::create('credit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->decimal('initial_amount', 10, 2)->nullable(); // initial amount with precision of 8 digits and 2 decimal places
            $table->decimal('Added_amount', 10, 2)->nullable(); // order amount with precision of 8 digits and 2 decimal places
            $table->decimal('final_amount', 10, 2)->nullable(); // final amount with precision of 8 digits and 2 decimal places
      
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_logs');
    }
};

