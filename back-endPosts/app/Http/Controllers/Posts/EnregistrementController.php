<?php

namespace App\Http\Controllers\Posts; // Vérifiez que le dossier est bien Controllers/Posts

use App\Http\Controllers\Controller;
use App\Models\Enregistrement;
use App\Models\Post;
use Illuminate\Http\Request;

class EnregistrementController extends Controller
{
    public function save($postId)
    {
        // IMPORTANT: Vérifiez dans phpMyAdmin que l'ID 1 existe dans la table 'users'
        $userId = auth()->id() ?? 1;

        $post = Post::findOrFail($postId);

        // On cherche si l'enregistrement existe déjà pour cet utilisateur et ce post
        $exists = Enregistrement::where('user_id', $userId)
            ->where('post_id', $postId)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Déjà enregistré'], 200);
        }

        $save = Enregistrement::create([
            'user_id' => $userId,
            'post_id' => $postId,
            'annonce_id' => null // Car c'est un post, pas une annonce
        ]);

        return response()->json([
            'message' => 'Enregistré avec succès',
            'is_saved' => true
        ]);
    }

    public function unsave($postId)
    {
        $userId = auth()->id() ?? 1;

        $save = Enregistrement::where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();

        if (!$save) {
            return response()->json(['message' => 'Non trouvé'], 404);
        }

        $save->delete();

        return response()->json(['message' => 'Retiré', 'is_saved' => false]);
    }
}
