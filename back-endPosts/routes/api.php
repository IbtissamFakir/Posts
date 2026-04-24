<?php

use App\Http\Controllers\CommentaireController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Posts\PostController;
use App\Http\Controllers\Posts\LikeController;
use App\Http\Controllers\Posts\EnregistrementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);


Route::post('/posts/{post}/like', [LikeController::class, 'toggle']);
Route::post('/posts/{id}/save', [EnregistrementController::class, 'save']);
Route::delete('/posts/{id}/unsave', [EnregistrementController::class, 'unsave']);


Route::get('/posts/{postId}/commentaires',[CommentaireController::class,'index']);
Route::post('/posts/{postId}/commentaires',[CommentaireController::class,'store']);
Route::get('/posts/{postId}/commentaires/{id}', [CommentaireController::class, 'show']);
Route::put('/posts/{postId}/commentaires/{id}', [CommentaireController::class, 'update']);