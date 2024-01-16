<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function forgot(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email']);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid email not Found'], 404);
        }

        $existingToken = DB::table('password_reset_tokens')
            ->where('email', $credentials['email'])
            ->first();

        if ($existingToken) {
            $token = $existingToken->token;
        } else {
            $token = Str::random(64);

            DB::table('password_reset_tokwhaens')->insert([
                'email' => $credentials['email'],
                'token' => $token,
                'created_at' => now(),
            ]);
        }

        $email = $credentials['email'];
        Mail::to($email)->send(new TestMail($email, $token));

        return response()->json(["msg" => 'Reset password link sent to your email.'], 200);
    }

    public function reset(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (!$tokenData) {
            return response()->json(['error' => 'Invalid token'], 401);
        } else {
            DB::table('users')
                ->where('email', $tokenData->email)
                ->update(['password' => Hash::make($request->input('password'))]);

            DB::table('password_reset_tokens')
                ->where('token', $token)
                ->delete();

            return response()->json(['msg' => 'Password reset successfully'], 200);
        }
    }
}
