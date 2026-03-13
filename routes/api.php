<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return response()->json([
        $request->user()
    ]);
})->middleware('auth:sanctum');

// 1|barVnSpS7sb5j9fqDITLnm3XVpurzKkF9DPTXYjEd2f5d2eb

Route::get('/test', function(){
    return response()->json([
        'message' => 'API fonction'
    ]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

#######
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/itineraries', [ItineraryController::class, 'store']);
    Route::get('/itineraries', [ItineraryController::class, 'index']);

    //favois
    Route::post('/itineraries/{id}/favorite', [ItineraryController::class, 'toggleFavorite']);

    //popular
    Route::get('/itineraries/popular', [ItineraryController::class, 'mostPopular']);

    #####statistique

    //Nombre total d'itinéraires par catégorie
    Route::get('/stats/itineraries-by-category', [ItineraryController::class, 'itinerariesByCategory']);

    //Nombre total des utilisateurs inscris par mois
    Route::get('/stats/users-by-month', [UserController::class, 'usersByMonth']);

    //logout

    Route::post('/logout', [AuthController::class, 'logout']);
});

