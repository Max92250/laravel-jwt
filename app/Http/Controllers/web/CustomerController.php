<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function CustomerCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'identifier' => 'required|string|unique:customers',

        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Create the customer
            $customer = Customer::create([
                'name' => $request->name,
                'identifier' => $request->identifier,
                // Add more fields for customer if needed
            ]);
            return redirect()->back()->with('success', 'Customer created successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurred

            dd($e);

            return back()->withInput()->withErrors(['error' => 'Failed to create customer. Please try again.']);
        }
    }

   /* public function Customer()
    {
        return view('admin.customercreate');
    }*/


    
  /*  public function User()
    {
        $customers = Customer::all();
        return view('admin.user_create', compact('customers'));
    }*/

    public function Customerdetails()
    {
        $customers = Customer::all();
        return view('admin.dashboard', ['customers' => $customers]);
    }
    
   

}
