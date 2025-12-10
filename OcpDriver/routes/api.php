<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ChauffeurAuthController;
use App\Http\Controllers\TripController;

// Client Auth
Route::post('/client/register', [ClientAuthController::class, 'register']);
Route::post('/client/login', [ClientAuthController::class, 'login']);

// Chauffeur Auth
Route::post('/chauffeur/register', [ChauffeurAuthController::class, 'register']);
Route::post('/chauffeur/login', [ChauffeurAuthController::class, 'login']);

// Protected Routes (after login)
Route::middleware('auth:api')->group(function() {
    // Client Routes
    Route::post('/trip/create', [TripController::class, 'createOrder']);
    Route::get('/trip/history', [TripController::class, 'history']);

    // Chauffeur Routes
    Route::get('/trips/pending', [TripController::class, 'pendingOrders']);
    Route::post('/trip/{id}/accept', [TripController::class, 'acceptOrder']);
    Route::post('/trip/{id}/complete', [TripController::class, 'completeOrder']);
});


