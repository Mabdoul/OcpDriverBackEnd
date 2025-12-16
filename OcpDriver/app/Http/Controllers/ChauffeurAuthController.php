<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chauffeur;
use Illuminate\Support\Facades\Hash;

class ChauffeurAuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email|unique:chauffeurs',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'cin' => 'nullable|string'
        ]);

        $data['password'] = Hash::make($data['password']);
        $chauffeur = Chauffeur::create($data);

        // Create Sanctum token
        $token = $chauffeur->createToken('chauffeur-token')->plainTextToken;

        return response()->json([
            'message' => 'Chauffeur registered successfully',
            'chauffeur' => $chauffeur,
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

        $chauffeur = Chauffeur::where('email', $credentials['email'])->first();

        if (!$chauffeur || !Hash::check($credentials['password'], $chauffeur->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $chauffeur->update(['status' => 'online']); // mark as online

        // Create Sanctum token
        $token = $chauffeur->createToken('chauffeur-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'chauffeur' => $chauffeur,
            'token' => $token
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $chauffeur = $request->user('chauffeur'); // get the authenticated chauffeur

        if ($chauffeur) {
            $chauffeur->update(['status' => 'offline']); // mark offline
            // Revoke all tokens for this chauffeur
            $chauffeur->tokens()->delete();
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}
