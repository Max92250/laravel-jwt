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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->decimal('price', 8, 2);
            $table->unsignedBigInteger('size_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->string('color');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
        $table->foreign('size_id')->references('id')->on('sizes')->onDelete('set null');
            // Define foreign key for product_id
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
