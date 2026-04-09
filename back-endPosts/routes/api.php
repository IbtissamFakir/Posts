<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\EnregistrementController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//afficher les posts
Route::get('/posts', [PostController::class, 'index']);
//creer un post et validation
Route::post('/posts', [\App\Http\Controllers\Api\PostController::class, 'store']);
//supprimer un post
Route::delete('/posts/{id}', [PostController::class, 'destroy']);
//  Likes
Route::post('/posts/{post}/like', [LikeController::class, 'like']);
Route::delete('/posts/{post}/unlike', [LikeController::class, 'unlike']);

// Enregistrements
Route::post('/posts/{post}/save', [EnregistrementController::class, 'save']);
Route::delete('/posts/{post}/unsave', [EnregistrementController::class, 'unsave']);
