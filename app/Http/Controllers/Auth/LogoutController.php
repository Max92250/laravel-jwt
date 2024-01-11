<?php

namespace App\Http\Controllers\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
 
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->update(['active' => 0]);
        }

        JWTAuth::invalidate(JWTAuth::getToken());


        return response()->json(['message' => 'User logged out successfully'], 200);
    }
    
}
