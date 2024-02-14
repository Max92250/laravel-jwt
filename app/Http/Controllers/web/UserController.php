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

    public function showLoginForm()
    {
        return view('User.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('laravel_token')->plainTextToken;

            // Store user email in session
            $request->session()->put('user', $user->username);
            // Check if the user is an admin
            if ($user->type === 'admin') {
                return redirect()->route('Admin.dashboard')->with('success', 'Login successful.');
            } else {
                return redirect()->route('product.dashboard')->with('success', 'Login successful.');
            }
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }

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

    public function CustomerUser($customerId)
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

        $users = User::where('type', '!=', 'admin')->get();

        foreach ($users as $user) {
            $customer = Customer::find($user->customer_id);
            $user->customer_name = $customer ? $customer->name : 'N/A';
        }

        $customers = Customer::all();
        // Pass users to the blade view
        return view('admin.user_details', compact('users', 'customers'));
    }

    public function logout(Request $request)
    {

        if ($request->user()) {
            $user = $request->user();

            $user->tokens()->delete();

            Auth::logout();

            return redirect('login')->with('success', 'Please log in.');
        }
    }
}
