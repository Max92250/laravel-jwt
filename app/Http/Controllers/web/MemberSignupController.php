<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Member;

class MemberSignupController extends Controller
{

    public function showSignupForm()
    {
        return view('User.signup');
    }

    public function signup(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'email' => 'required|email|unique:members',
            'username' => 'required|unique:members',
            'password' => 'required|min:6',
            'badge_id' => 'required|unique:members',
        ]);

        // Create a new member
        $member = Member::create([
            'email' => $validatedData['email'],
            'username' => $validatedData['username'],
            'password' => bcrypt($validatedData['password']), // Hash the password
            'badge_id' => $validatedData['badge_id'],
            'customer_id' => auth()->user()->customer_id,
        ]);

        // Optionally, you can authenticate the member after signup
        // auth()->login($member);

        // Redirect or respond with a success message
        return redirect()->route('dashboard')->with('success', 'Member signed up successfully!');
    }
}
