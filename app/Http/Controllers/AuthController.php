<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(SignUpRequest $request)
    {
        $request->validated();
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json(['message' => "Register successfully!"]);
    }

    public function login(SignInRequest $request)
    {
        $request->validated();
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => "Invalid credentials"
            ], 401);
        }
        $token = $user->createToken($user->first_name . '-AuthToken')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function logout()
    {
        auth()->user()->tokens->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
