<?php

use App\Http\Controllers\Post\EnregistrementController;
use App\Http\Controllers\Post\LikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Routes pour les fonctionnalités des posts : likes et enregistrements
|--------------------------------------------------------------------------
*/

// 🔹 Routes publiques (juste pour test temporaire)
Route::get('/posts', function () {
    return \App\Models\Post::all();
});


/*
|--------------------------------------------------------------------------
| Routes sécurisées (auth:sanctum)
|--------------------------------------------------------------------------
| IMPORTANT: À activer quand vous utilisez l’authentification
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // 🔸 Likes
    Route::post('/posts/{post}/like', [LikeController::class, 'like']);
    Route::delete('/posts/{post}/unlike', [LikeController::class, 'unlike']);

    // 🔸 Enregistrements (Saved posts)
    Route::post('/posts/{post}/save', [EnregistrementController::class, 'save']);
    Route::delete('/posts/{post}/unsave', [EnregistrementController::class, 'unsave']);

    // 🔸 Utilisateur connecté
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});


/*
|--------------------------------------------------------------------------
| Routes TEMPORAIRES pour test navigateur (À SUPPRIMER avant push)
|--------------------------------------------------------------------------
*/

Route::get('/test-like/{id}', function ($id) {
    return app(\App\Http\Controllers\Post\LikeController::class)->like($id);
});

Route::get('/test-unlike/{id}', function ($id) {
    return app(\App\Http\Controllers\Post\LikeController::class)->unlike($id);
});

Route::get('/test-save/{id}', function ($id) {
    return app(\App\Http\Controllers\Post\EnregistrementController::class)->save($id);
});

Route::get('/test-unsave/{id}', function ($id) {
    return app(\App\Http\Controllers\Post\EnregistrementController::class)->unsave($id);
});
