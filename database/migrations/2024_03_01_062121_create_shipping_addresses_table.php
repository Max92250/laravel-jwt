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
        Schema::create('checkout_details', function (Blueprint $table) {
            $table->id();
          
            $table->string('payment_method')->comment('The payment method used for billing');
            $table->string('card_number')->comment('The credit card number associated with the payment method');
            $table->string('cart_key')->comment('The key associated with the cart details');
            $table->string('expiration_date')->comment('The expiration date of the credit card');
            $table->string('cvv')->comment('The CVV (Card Verification Value) of the credit card');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_details');
    }
};
