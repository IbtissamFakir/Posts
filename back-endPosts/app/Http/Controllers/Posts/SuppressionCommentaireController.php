<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuppressionCommentaireController extends Controller
{
    /**
     * Supprime un commentaire d'un post donné
     *
     * @param Request $request
     * @param int $postId
     * @param int $commentaireId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $postId, $commentaireId)
    {
        // 1. COMMENTE la vérification d'authentification
        /*
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Vous devez être connecté pour supprimer un commentaire.'
            ], 401);
        }
        */

        $post = Post::find($postId);
        if (!$post) {
            return response()->json(['message' => 'Post non trouvé.'], 404);
        }

        $commentaire = Commentaire::where('id', $commentaireId)
            ->where('post_id', $postId)
            ->first();

        if (!$commentaire) {
            return response()->json(['message' => 'Commentaire non trouvé pour ce post.'], 404);
        }

        // Si le commentaire appartient à l'user 1, écris : $userId = 1;
        $fakeUserId = $commentaire->user_id;

        // 3. Modifie la vérification de propriété avec ta variable temporaire
        if ($commentaire->user_id !== $fakeUserId) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $commentaire->delete();

        return response()->json([
            'message' => 'Commentaire supprimé avec succès.'
        ], 200);
    }
}


/* 1. Détection automatique de l'utilisateur connecté

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuppressionCommentaireController extends Controller
{
    public function destroy(Request $request, $postId, $commentaireId)
    {
        // 1. Détection automatique de l'utilisateur connecté
        if (!Auth::check()) {
            return response()->json(['message' => 'Vous devez être connecté.'], 401);
        }

        $post = Post::find($postId);
        if (!$post) {
            return response()->json(['message' => 'Post non trouvé.'], 404);
        }

        $commentaire = Commentaire::where('id', $commentaireId)
            ->where('post_id', $postId)
            ->first();

        if (!$commentaire) {
            return response()->json(['message' => 'Commentaire non trouvé.'], 404);
        }

        // 2. Vérification automatique : est-ce que l'utilisateur est le propriétaire ?
        if ($commentaire->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à supprimer ce commentaire.'], 403);
        }

        $commentaire->delete();

        return response()->json(['message' => 'Commentaire supprimé avec succès.'], 200);
    }
}*/
