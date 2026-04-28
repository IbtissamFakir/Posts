<?php

use App\Http\Controllers\CommentaireController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes des posts
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);

// Routes des likes et enregistrements
Route::post('/posts/{post}/like', [LikeController::class, 'toggle']);
Route::post('/posts/{id}/save', [EnregistrementController::class, 'save']);
Route::delete('/posts/{id}/unsave', [EnregistrementController::class, 'unsave']);


Route::get('/posts/{postId}/commentaires',[CommentaireController::class,'index']);
Route::post('/posts/{postId}/commentaires',[CommentaireController::class,'store']);
Route::get('/posts/{postId}/commentaires/{id}', [CommentaireController::class, 'show']);
Route::put('/posts/{postId}/commentaires/{id}', [CommentaireController::class, 'update']);
