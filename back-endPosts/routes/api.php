<?php

use App\Http\Controllers\Posts\EnregistrementController;
use App\Http\Controllers\Posts\LikeController;
use App\Http\Controllers\Posts\PostController;
use App\Http\Controllers\Posts\SignalementController;
use App\Http\Controllers\Posts\SuppressionCommentaireController;
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

// Route pour supprimer un commentaires
Route::delete('/posts/{postId}/commentaires/{commentaireId}', [SuppressionCommentaireController::class, 'destroy']);
// Signaler un commentaire
Route::post('/posts/{postId}/commentaires/{commentaireId}/signaler', [SignalementController::class, 'signalerCommentaire']);

Route::get('/admin/commentaires-signales', [SignalementController::class, 'commentairesSignales']);
Route::delete('/admin/commentaires/{commentaireId}/valider-suppression', [SignalementController::class, 'validerSuppression']);
Route::post('/admin/commentaires/{commentaireId}/rejeter-signalement', [SignalementController::class, 'rejeterSignalement']);


// --- LE RESTE DU GROUPE (vide ou à laisser pour plus tard) ---
Route::middleware('auth:sanctum')->group(function () {
    // Vide pour le moment
});




//Détection automatique de l'utilisateur
/*
use App\Http\Controllers\Posts\EnregistrementController;
use App\Http\Controllers\Posts\LikeController;
use App\Http\Controllers\Posts\PostController;
use App\Http\Controllers\Posts\SignalementController;
use App\Http\Controllers\Posts\SuppressionCommentaireController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- ROUTES PUBLIQUES (Accessibles sans être connecté) ---
Route::get('/posts', [PostController::class, 'index']);

// --- ROUTES PROTÉGÉES (Détection automatique de l'utilisateur) ---
Route::middleware('auth:sanctum')->group(function () {

    // Utilisateur actuel
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Actions sur les Posts
    Route::post('/posts', [PostController::class, 'store']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    // Likes et Enregistrements
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle']);
    Route::post('/posts/{id}/save', [EnregistrementController::class, 'save']);
    Route::delete('/posts/{id}/unsave', [EnregistrementController::class, 'unsave']);

    // Gestion des Commentaires
    Route::delete('/posts/{postId}/commentaires/{commentaireId}', [SuppressionCommentaireController::class, 'destroy']);
    Route::post('/posts/{postId}/commentaires/{commentaireId}/signaler', [SignalementController::class, 'signalerCommentaire']);

    //  ROUTES ADMIN
    Route::get('/admin/commentaires-signales', [SignalementController::class, 'commentairesSignales']);
    Route::delete('/admin/commentaires/{commentaireId}/valider-suppression', [SignalementController::class, 'validerSuppression']);
    Route::post('/admin/commentaires/{commentaireId}/rejeter-signalement', [SignalementController::class, 'rejeterSignalement']);
});
*/
