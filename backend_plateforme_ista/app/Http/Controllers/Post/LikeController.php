<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;

class LikeController extends Controller
{
    // Ajouter un like
    public function like($postId)
    {
        $user = \App\Models\User::first(); // utilisateur test

        $post = Post::findOrFail($postId);

        // vérifier si déjà liké
        $exists = Like::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already liked']);
        }

        $like = Like::create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        return response()->json($like);
    }

    // Supprimer like
    public function unlike($postId)
    {
        $user = \App\Models\User::first();

        $like = Like::where('user_id', $user->id)
            ->where('post_id', $postId)
            ->first();

        if (!$like) {
            return response()->json(['message' => 'Like not found']);
        }

        $like->delete();

        return response()->json(['message' => 'Unliked']);
    }
}
