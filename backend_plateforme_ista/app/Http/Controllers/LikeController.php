<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    //  Ajouter un like à un post
    public function like(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);

        // Vérifier si l'utilisateur a déjà liké
        $like = Like::where('post_id', $postId)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($like) {
            return response()->json(['message' => 'Vous avez déjà liké ce post'], 400);
        }

        // Créer le like
        $like = Like::create([
            'user_id' => $request->user()->id,
            'post_id' => $postId,
        ]);

        return response()->json(['message' => 'Post liké !', 'like' => $like], 201);
    }

    // Retirer un like
    public function unlike(Request $request, $postId)
    {
        $like = Like::where('post_id', $postId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$like) {
            return response()->json(['message' => 'Vous n\'avez pas liké ce post'], 400);
        }

        $like->delete();

        return response()->json(['message' => 'Like retiré !']);
    }
}
