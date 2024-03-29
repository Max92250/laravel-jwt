<?php

namespace App\Http\Controllers\Auth;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $request->all();

        $data['created_at'] = Carbon::now();


        $user = $this->create($data);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8',  'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'gender' => ['required', 'string', 'in:Male,Female'],
            'department' => ['nullable', 'string'],
            'phone_number' => ['required', 'string', 'min:8'],
        ]);
    }

    private function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'gender' => $data['gender'],
            'department' => $data['department'] ?? null,
            'phone_number' => $data['phone_number'],
            'created_at' => $data['created_at'],
        ]);

       
    }
}
