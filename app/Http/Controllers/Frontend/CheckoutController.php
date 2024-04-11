<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;

class CheckoutController extends Controller
{

    public function show($cart_id)
    {
        // Retrieve the cart details using the cart ID
        $cart = Cart::findOrFail($cart_id);

        if ($cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty. Please add items to your cart before proceeding to checkout.');
        }

        $member = auth()->guard('members')->user();
        $cart = $member->cart;
        $shipments = $member->shipments;

        // If the member has a shipment address, pass it to the view
        return view('components.member.checkout', ['cart' => $cart, 'shipments' => $shipments]);

    }


/*
public function checkout(Request $request)
{
// Retrieve the user ID
$member = auth()->guard('members')->user();
// Process checkout
$validatedData = $request->validate([
'name' => 'required|string|max:255',
'address_line1' => 'required|string|max:255',
'address_line2' => 'nullable|string|max:255',
'city' => 'required|string|max:255',
'state' => 'required|string|max:255',
'postal_code' => ['required', 'string', 'regex:/^\d{6}$/'],
'payment_method' => ['required', Rule::in(['credit_card', 'paypal', 'debit_card'])],
'card_number' => ['required_if:payment_method,credit_card,debit_card', 'string', 'max:16', new ValidCreditCard],
'expiration_date' => 'required_if:payment_method,credit_card,debit_card|string|max:20',
'cvv' => 'required_if:payment_method,credit_card,debit_card|string|max:3',

]);

$checkoutData = [
'member_id' => $member->id,
'name' => $request->input('name'),
'address_line1' => $request->input('address_line1'),
'address_line2' => $request->input('address_line2'),
'city' => $request->input('city'),
'state' => $request->input('state'),
'postal_code' => $request->input('postal_code'),
'payment_method' => $request->input('payment_method'),
'cart_key' => $request->input('card_id'),
// Exclude card_number, expiration_date, and cvv if payment method is 'cash'
'card_number' => $request->input('card_number'),
'expiration_date' => $request->input('expiration_date'),
'cvv' =>$request->input('cvv'),
];
dd($checkoutData);
// Redirect to a success page or show a success message
return redirect()->route('checkout.success')->with('success', 'Checkout successful!');
}
 */
}
