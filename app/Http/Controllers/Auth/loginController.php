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
    $credentials = $request->only('email', 'password');

    $validator = Validator::make($credentials, [
        'email' => 'required|string',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->messages()], 422);
    }

    try {
        $token = JWTAuth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = User::where('email', $credentials['email'])->first();

        $user->update(['active' => true]);

       
        return response()->json([
            'success' => true,
            'data' => ['token' => $token],
        ]);

    } catch (JWTException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Could not create token.',
        ], 500);
    }
}
    
    
}
