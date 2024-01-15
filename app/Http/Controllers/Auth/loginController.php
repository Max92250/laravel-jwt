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

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $this->validateCredentials($credentials);

        $token = $this->attemptLogin( $credentials);
        $user = $this->getUserByEmail($credentials['email']);
        $this->updateUserStatus($user, true);

        return $this->respondWithToken($token);
    }

    private function validateCredentials(array $credentials)
    {
        $validator = Validator::make($credentials, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->respondValidationError($validator);
        }
    }

    private function attemptLogin( array $credentials)
    {
        try {
            $token = JWTAuth::attempt($credentials);

            if (!$token) {
                return $this->respondInvalidCredentials();
            }

            return $token;
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Unable to generate token'], 500);
        }
    }

    private function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    private function updateUserStatus(User $user, $status)
    {
        if ($user->active == 0) {
            $user->update(['active' => 1]);
        }
    }

    private function respondValidationError($validator)
    {
        return response()->json(['success' => false, 'error' => $validator->messages()], 422);
    }

    private function respondInvalidCredentials()
    {
        return response()->json(['success' => false, 'message' => 'Invalid credentials.'], 401);
    }

    private function respondWithToken($token)
    {
        return response()->json(['success' => true, 'data' => ['token' => $token]], 200);
    }
}
