<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Chauffeur;

class TripController extends Controller
{
    // Client creates a trip
    public function create(Request $request)
    {
        $data = $request->validate([
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'drop_lat' => 'required',
            'drop_lng' => 'required',
        ]);

        $data['user_id'] = auth()->user()->id;
        $data['status'] = 'pending';

        $trip = Trip::create($data);

        return response()->json([
            'message' => 'Trip created',
            'trip' => $trip
        ]);
    }

    // Chauffeur gets pending trips (ONLY IF ONLINE)
    public function pendingTrips()
    {
        $chauffeur = auth('chauffeur')->user();

        if ($chauffeur->status !== 'online') {
            return response()->json([
                'message' => 'You must be online to receive trips'
            ], 403);
        }

        $trips = Trip::where('status', 'pending')->get();

        return response()->json($trips);
    }

    // Chauffeur accepts a trip
    public function acceptTrip(Request $request, $tripId)
    {
        $chauffeur = auth('chauffeur')->user();

        $trip = Trip::findOrFail($tripId);

        if ($trip->status !== 'pending') {
            return response()->json(['message' => 'Trip already taken'], 400);
        }

        $trip->update([
            'status' => 'accepted',
            'chauffeur_id' => $chauffeur->id
        ]);

        return response()->json([
            'message' => 'Trip accepted',
            'trip' => $trip
        ]);
    }

    // Chauffeur completes trip
    public function completeTrip($tripId)
    {
        $chauffeur = auth('chauffeur')->user();

        $trip = Trip::findOrFail($tripId);

        if ($trip->chauffeur_id !== $chauffeur->id) {
            return response()->json(['message' => 'This is not your trip'], 403);
        }

        $trip->update([
            'status' => 'completed'
        ]);

        return response()->json([
            'message' => 'Trip completed'
        ]);
    }
}
