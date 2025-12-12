<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chauffeur;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

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

        $token = JWTAuth::fromUser($chauffeur);

        return response()->json([
            'message' => 'Chauffeur registered',
            'chauffeur' => $chauffeur,
            'token' => $token
        ]);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        if (!$token = auth('chauffeur')->attempt($credentials)) {
            return response()->json(['error'=>'Invalid credentials'],401);
        }

        $chauffeur = auth('chauffeur')->user();
        $chauffeur->update(['status'=>'online']); // online

        return response()->json([
            'message'=>'Login successful',
            'chauffeur'=>$chauffeur,
            'token'=>$token
        ]);
    }

    // Logout
    public function logout()
    {
        $chauffeur = auth('chauffeur')->user();
        if($chauffeur){
            $chauffeur->update(['status'=>'offline']); // offline
        }
        auth('chauffeur')->logout();

        return response()->json(['message'=>'Logged out']);
    }
}
