<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

        // Create Sanctum token
        $token = $user->createToken('client-token')->plainTextToken;

        return response()->json([
            'message' => 'Client registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Create Sanctum token
        $token = $user->createToken('client-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $user = $request->user(); // authenticated user

        if ($user) {
            $user->tokens()->delete(); // revoke all tokens
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}
