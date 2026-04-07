<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;

//  Routes pour les likes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts/{post}/like', [LikeController::class, 'like']);
    Route::delete('/posts/{post}/unlike', [LikeController::class, 'unlike']);
});

// Route pour récupérer l'utilisateur connecté
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
