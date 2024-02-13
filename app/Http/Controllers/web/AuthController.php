<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('Auth.login');
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
            return redirect()->route('products.index')->with('success', 'Login successful.');
        }
    }

    return redirect()->back()->with('error', 'Invalid credentials.');
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
