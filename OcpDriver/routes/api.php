<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ChauffeurAuthController;
use App\Http\Controllers\TripController;

// ================= PUBLIC ROUTES =================
// Client Auth
Route::post('/client/register', [ClientAuthController::class, 'register']);
Route::post('/client/login', [ClientAuthController::class, 'login']);

// Chauffeur Auth
Route::post('/chauffeur/register', [ChauffeurAuthController::class, 'register']);
Route::post('/chauffeur/login', [ChauffeurAuthController::class, 'login']);

// ================= PROTECTED ROUTES (Sanctum) =================
Route::middleware('auth:sanctum')->group(function() {

    // -------- CLIENT --------
    Route::prefix('client')->group(function() {
        Route::post('/trip/create', [TripController::class, 'createOrder']);
        Route::get('/trip/history', [TripController::class, 'history']);
        Route::post('/logout', [ClientAuthController::class, 'logout']);
        Route::get('/trip/latest', [TripController::class, 'latestTrip']);

    });

    // -------- CHAUFFEUR --------
    Route::prefix('chauffeur')->group(function() {
        Route::get('/trips/pending', [TripController::class, 'pendingOrders']);
        Route::post('/trip/{id}/accept', [TripController::class, 'acceptOrder']);
        Route::post('/trip/{id}/refuse', [TripController::class, 'refuseOrder']); // added
        Route::post('/trip/{id}/complete', [TripController::class, 'completeOrder']);
        Route::get('/trip/history', [TripController::class, 'completedTrips']); // optional
        Route::post('/logout', [ChauffeurAuthController::class, 'logout']);

    });

});

