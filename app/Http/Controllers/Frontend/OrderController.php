<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Rules\ValidCreditCard;
use App\Rules\ValidExpirationDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{

    public function placeOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            $member = auth()->guard('members')->user();
    
            // Validate the incoming request data
    
            // Calculate total amount
            $totalAmount = $request->input('selected_total_price');
    
            // Calculate remaining balance
            $remainingBalance = $member->credits()->sum('amount') - $totalAmount;
    
            // Get the credit IDs
            $creditIds = $member->credits()->pluck('credits.id')->toArray();

   
            // Check if remaining balance is sufficient
            if ($remainingBalance >= 0) {

                $totalQuantity = 0;
                foreach ($member->cart->items as $cartItem) {
                    $totalQuantity += $cartItem->quantity;
                }

                

                // Create order record
                $orderData = [
                    'member_id' => $member->id,
                    'shipment_id' => $request->input('selected_shipment_id'),
                    'payment_id' => $creditIds[0], // Use the first credit ID as the payment ID
                    'total' => $totalAmount,
                    'quantity' => $totalQuantity, // Include total quantity here
                ];
    
                $order = Order::create($orderData);

                    // Deduct amount from member's credit
                    $credit = $member->credits();
                    $newCreditAmount = $credit->sum('amount') - $totalAmount;
                   $credit->update(['amount' => $newCreditAmount]);
    
                // Attach products to the order
                foreach ($member->cart->items as $cartItem) {
                    $order->products()->attach($cartItem->product->id, [
                        'item_id' => $cartItem->item->id,
                        'quantity' => $cartItem->quantity,
                    ]);
                }
    
                // Delete cart items
                $member->cart->items()->delete();
    
                DB::commit();
    
                return redirect()->route('orders.index')->with('success', 'Order placed successfully.');
    
                // Redirect to a success page or return a success response
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Insufficient Balance');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
    
            return redirect()->back()->with('error', 'Failed to place order');
        }
    }
    
    public function index()
    {
        $member = auth()->guard('members')->user();
        $orders = $member->orders()->with('products')->get();


       return  view('components.member.orderlist',compact('orders'));
    }

}
