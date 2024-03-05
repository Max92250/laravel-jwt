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
            $request->validate([
                'payment_method' => ['required', Rule::in(['credit_card', 'paypal', 'debit_card'])],
                'card_number' => ['required_if:payment_method,credit_card,debit_card', 'string', 'max:16', new ValidCreditCard],
                'expiration_date' => ['required_if:payment_method,credit_card,debit_card', 'string', 'max:20', new ValidExpirationDate],
                'cvv' => 'required_if:payment_method,credit_card,debit_card|string|max:3',
            ]);

            // Calculate total amount
            $totalAmount = $request->input('selected_total_price');

            // Calculate remaining balance
            $remainingBalance = $member->credits()->sum('amount') - $totalAmount;

            // Check if remaining balance is sufficient
            if ($remainingBalance >= 0) {
                // Create payment record
                $paymentData = [
                    'member_id' => $member->id,
                    'payment_method' => $request->payment_method,
                    'card_number' => $request->card_number,
                    'expiration_date' => $request->expiration_date,
                    'cvv' => $request->cvv,
                ];

                $payment = Payment::create($paymentData);

                // Create order record
                $orderData = [
                    'member_id' => $member->id,
                    'shipment_id' => $request->input('selected_shipment_id'),
                    'payment_id' => $payment->id,
                    'total' => $totalAmount,
                ];

                $order = Order::create($orderData);

                // Deduct amount from member's credit
                $credit = $member->credits()->first();
                $credit->amount -= $totalAmount;
                $credit->save();

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

                // Redirect to a success page or return a success response
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Insufficient Balance');
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'failed to order');

        }
    }

    public function index()
    {
        $member = auth()->guard('members')->user();
        $orders = $member->orders()->with('products')->get();

        return $orders;

       return  view('components.member.orderlist',compact('orders'));
    }

}
