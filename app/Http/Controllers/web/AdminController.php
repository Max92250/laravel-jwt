<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
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

    public function UserCreate(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'username' => 'required|string|unique:users,username',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $userData = [
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'username' => $request->username,
        ];

        if ($request->filled('customer_id')) {
            $userData['customer_id'] = $request->customer_id;
        } else {
            // If no customer ID is selected, set the type to "admin"
            $userData['type'] = 'admin';
        }

        User::create($userData);

        return redirect()->route('users.details')->with('success', 'User created successfully');
    } catch (\Exception $e) {
        dd($e);

        return back()->withInput()->withErrors(['error' => 'Failed to create Users. Please try again.']);
    }
}

    
  /*  public function User()
    {
        $customers = Customer::all();
        return view('admin.user_create', compact('customers'));
    }*/

    public function dashboard()
    {
        $customers = Customer::all();
        return view('admin.dashboard', ['customers' => $customers]);
    }
    
    public function UserDashboard($customerId)
    {
        // Fetch user details based on the customer ID
        $user = User::where('customer_id', $customerId)->get();

        if (!$user) {
            return back()->withErrors(['error' => 'User not found.']);
        }

        return view('admin.user_dashboard', compact('user'));
    }

    public function userdetails()
    {
        
        $users = User::all();
        $customers = Customer::all();
        // Pass users to the blade view
        return view('admin.user_details', compact('users','customers'));
    }
}
