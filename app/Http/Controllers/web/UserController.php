<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'username' => 'required|string|unique:users,username',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userData = [
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'username' => $request->username,
        ];

        if ($request->filled('customer_id')) {
            $userData['customer_id'] = $request->customer_id;
            $userData['created_by'] = $request->user()->id;
            $userData['updated_by'] = $request->user()->id;
        } else {
            // If no customer ID is selected, set the type to "admin"
            $userData['type'] = 'admin';
        }

        User::create($userData);

        return redirect()->route('users.details')->with('success', 'User created successfully');

    }

    public function customer_users($customerId)
    {
        // Fetch user details based on the customer ID
        $user = User::where('customer_id', $customerId)->get();

        if (!$user) {
            return back()->withErrors(['error' => 'User not found.']);
        }

        return view('admin.user_dashboard', compact('user'));
    }

    public function show()
    {
        $users = User::where('type', '!=', 'admin')
        ->with('creator','updator','createdBy')
        ->get();
        

        $customers = Customer::all();
        // Pass users to the blade view
        return view('admin.user_details', compact('users', 'customers'));
    }
    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        // Validate the incoming data from the client-side
        $validator = $request->validate([
            'username' => 'sometimes|required|string|unique:users,username,' . $id,
        ]);

        // Update the username if changes are made
        if ($request->filled('username')) {
            $user->username = $request->input('username');
        }
        $adminUser = Auth::user();
        $user->updated_by = $adminUser->id;

        // Save the changes
        $user->save();

        return redirect()->back()->with('success', 'User details updated successfully.');

    }

}
