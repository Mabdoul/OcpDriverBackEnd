<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ChauffeurAuthController;
use App\Http\Controllers\TripController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Client Auth
Route::post('/client/register', [ClientAuthController::class, 'register']);
Route::post('/client/login', [ClientAuthController::class, 'login']);

// Chauffeur Auth
Route::post('/chauffeur/register', [ChauffeurAuthController::class, 'register']);
Route::post('/chauffeur/login', [ChauffeurAuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Sanctum)
|--------------------------------------------------------------------------
| Routes that require authentication via Sanctum token
*/

Route::middleware('auth:sanctum')->group(function() {

    // Client Routes
    Route::prefix('client')->group(function() {
        Route::post('/trip/create', [TripController::class, 'createOrder']);
        Route::get('/trip/history', [TripController::class, 'history']);
        Route::post('/logout', [ClientAuthController::class, 'logout']);
    });

    // Chauffeur Routes
    Route::prefix('chauffeur')->group(function() {
        Route::get('/trips/pending', [TripController::class, 'pendingOrders']);
        Route::post('/trip/{id}/accept', [TripController::class, 'acceptOrder']);
        Route::post('/trip/{id}/complete', [TripController::class, 'completeOrder']);
        Route::post('/logout', [ChauffeurAuthController::class, 'logout']);
    });

});
