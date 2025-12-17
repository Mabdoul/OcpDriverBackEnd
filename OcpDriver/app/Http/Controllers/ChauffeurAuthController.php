<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chauffeur;
use Illuminate\Support\Facades\Hash;

class ChauffeurAuthController extends Controller
{
    // ================= REGISTER =================
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:chauffeurs',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'cin' => 'nullable|string'
        ]);

        $data['password'] = Hash::make($data['password']);

        $chauffeur = Chauffeur::create($data);

        $token = $chauffeur->createToken('chauffeur-token')->plainTextToken;

        return response()->json([
            'message' => 'Chauffeur registered successfully',
            'chauffeur' => $chauffeur,
            'token' => $token,
            'role' => 'chauffeur'
        ], 201);
    }

    // ================= LOGIN =================
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $chauffeur = Chauffeur::where('email', $credentials['email'])->first();

        if (!$chauffeur || !Hash::check($credentials['password'], $chauffeur->password)) {
            return response()->json([
                'error' => 'Invalid credentials'
            ], 401);
        }

        $chauffeur->update(['status' => 'active']);

        $token = $chauffeur->createToken('chauffeur-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'chauffeur' => $chauffeur,
            'token' => $token,
            'role' => 'chauffeur'
        ]);
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        $chauffeur = $request->user();

        if ($chauffeur) {
            $chauffeur->update(['status' => 'inactive']);
            $chauffeur->tokens()->delete();
        }

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
