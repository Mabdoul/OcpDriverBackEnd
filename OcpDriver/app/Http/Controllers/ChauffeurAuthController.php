<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ClientAuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string'
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Client registered',
            'user' => $user,
            'token' => $token
        ]);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error'=>'Invalid credentials'],401);
        }

        $user = auth()->user();

        return response()->json([
            'message'=>'Login successful',
            'user'=>$user,
            'token'=>$token
        ]);
    }
}
