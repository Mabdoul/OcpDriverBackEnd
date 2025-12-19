<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Chauffeur;

class TripController extends Controller
{
    // ================= CLIENT =================
    // Client creates a trip
    public function createOrder(Request $request)
    {
        $data = $request->validate([
            'start_lat' => 'required',
            'start_lng' => 'required',
            'end_lat' => 'required',
            'end_lng' => 'required',
        ]);

        $data['user_id'] = auth()->user()->id;
        $data['status'] = 'pending';

        $trip = Trip::create($data);

        return response()->json([
            'message' => 'Trip created',
            'trip' => $trip
        ]);
    }

    // Client sees history of their trips
    public function history()
    {
        $user = auth()->user();
        $trips = Trip::where('user_id', $user->id)->get();
        return response()->json($trips);
    }

    // ================= CHAUFFEUR =================
    // Get pending trips (for online/active chauffeurs)
    public function pendingOrders()
    {
        $chauffeur = auth('chauffeur')->user();

        if ($chauffeur->status !== 'active') {
            return response()->json(['message'=>'You must be active to see trips'], 403);
        }

        $trips = Trip::where('status','pending')->get();

        return response()->json($trips);
    }

    // Accept a trip
    public function acceptOrder($id)
    {
        $chauffeur = auth('chauffeur')->user();
        $trip = Trip::findOrFail($id);

        if ($trip->status !== 'pending') {
            return response()->json(['message'=>'Trip already taken'],400);
        }

        $trip->update([
            'status'=>'accepted',
            'chauffeur_id'=>$chauffeur->id
        ]);

        return response()->json([
            'message'=>'Trip accepted',
            'trip'=>$trip
        ]);
    }

    // Refuse a trip
    public function refuseOrder($id)
    {
        $chauffeur = auth('chauffeur')->user();
        $trip = Trip::findOrFail($id);

        if ($trip->status !== 'pending') {
            return response()->json(['message'=>'Trip cannot be refused'], 400);
        }

        // Nothing to update, trip stays pending
        return response()->json(['message'=>'Trip refused']);
    }

    // Complete a trip
    public function completeOrder($id)
    {
        $chauffeur = auth('chauffeur')->user();
        $trip = Trip::findOrFail($id);

        if ($trip->chauffeur_id !== $chauffeur->id) {
            return response()->json(['message'=>'This is not your trip'],403);
        }

        $trip->update(['status'=>'completed']);

        return response()->json(['message'=>'Trip completed']);
    }

    // Chauffeur sees completed trips (optional)
    public function completedTrips()
    {
        $chauffeur = auth('chauffeur')->user();
        $trips = Trip::where('chauffeur_id', $chauffeur->id)
                     ->where('status', 'completed')
                     ->get();
        return response()->json($trips);
    }
}
