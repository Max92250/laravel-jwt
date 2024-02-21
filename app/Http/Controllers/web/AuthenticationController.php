<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AuthenticationController extends Controller
{

    public function loginform()
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
            
            return redirect()->route('dashboard')->with('success', 'Login successful.');
            
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
