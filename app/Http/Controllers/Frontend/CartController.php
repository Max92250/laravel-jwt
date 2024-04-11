<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Item;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function addItemToCart(Request $request)
    {
        $itemId = $request->input('item_id');

        // Retrieve the user ID
        $member = auth()->guard('members')->user();
        $customer_id = $member->customer->id;

        $memberId = $member->id;

        // Check if a cart exists for the current user
        $cart = Cart::where('member_id', $memberId)
            ->where('customer_id', $customer_id)
            ->first();

        // If a cart doesn't exist, create a new one
        if (!$cart) {
            // Create a new cart with the user ID and customer ID
            $cart = Cart::create([
                'member_id' => $memberId,
                'customer_id' => $customer_id,
            ]);
        }

        $existingCartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_item_id', $itemId)
            ->first();
        if ($existingCartItem) {
            $existingCartItem->increment('quantity'); // Increment the quantity by 1
        } else {
            // Otherwise, create a new cart item
            $item = Item::findOrFail($itemId); // Assuming Item is the model for items
            CartItem::create([
                'cart_id' => $cart->id,
                'product_item_id' => $itemId,
                'sku' => $item->sku,
                'product_id' => $item->product_id,
                'price' => $item->price,
                'quantity' => 1, // Set the initial quantity to 1
            ]);
        }

    }

    public function show()
    {

        $member = auth()->guard('members')->user();

        $cart = $member->cart;
       
        return view('components.member.cart', ['cart' => $cart]);

    }
    public function updateQuantity(Request $request)
    {
        $itemId = $request->itemId;
        $newQuantity = $request->quantity;

        // Find the cart item by its ID
        $cartItem = CartItem::findOrFail($itemId);

        // Update the quantity of the cart item
        // Check if the new quantity exceeds the available quantity for the product item
        if ($newQuantity > $cartItem->item->quantity) {
            // If it does, set the new quantity to the available quantity
            $newQuantity = $cartItem->item->quantity;
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        // Calculate the new price
        $newPrice = $cartItem->price * $newQuantity;

        // Return the new price in the response
        return response()->json(['price' => $newPrice]);
    }

    public function deleteItem(Request $request)
    {
        // Retrieve the item ID from the request
        $itemId = $request->itemId;

        // Find the item in the database
        $item = CartItem::findOrFail($itemId);

        // Delete the item
        $item->delete();

        return response()->json(['success' => true]);
    }

    public function updateCartCounter(Request $request)
    {

        $user = auth()->guard('members')->user();
        $itemCount = $user->cart->items->count();
        return response()->json(['itemCount' => $itemCount]);

    }
}
