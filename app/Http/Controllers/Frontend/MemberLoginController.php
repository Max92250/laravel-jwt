<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
class MemberLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('User.member-login');
    }

    public function login(Request $request)
    {
        // Validate the login request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (Auth::guard('members')->attempt($credentials)) {
            // Authentication successful, redirect to member dashboard
            return redirect()->route('member.dashboard');
        }

        // Authentication failed, redirect back with error message
        return back()->withErrors(['email' => 'Invalid email or password']);
    }

    public function logout()
    {
        // Logout the member
        Auth::guard('member')->logout();

        // Redirect to the login page
        return redirect()->route('member.login');
    }
}
