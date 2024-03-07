<?php

namespace App\Http\Controllers\Auth;
use JWTAuth;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class loginController extends Controller
{
    public function login(Request $request)
{
    // Validate user input
    $credentials = $request->only('email', 'password');
    $validator = Validator::make($credentials, [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => 'Invalid credentials'], 422);
    }

    // Attempt to authenticate user
    if (!$token = JWTAuth::attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Authentication successful, return token in response header
    return response()->json(['token' => $token])->header('Authorization', 'Bearer ' . $token);
}
    
    
}
