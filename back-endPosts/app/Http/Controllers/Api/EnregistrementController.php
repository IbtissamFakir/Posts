<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Enregistrement;
use App\Models\Post;

class EnregistrementController extends Controller
{
    // Enregistrer un post
    public function save($postId)
    {
        $user = \App\Models\User::first();

        $post = Post::findOrFail($postId);

        $exists = Enregistrement::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already saved']);
        }

        $save = Enregistrement::create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        return response()->json($save);
    }

    // Supprimer enregistrement
    public function unsave($postId)
    {
        $user = \App\Models\User::first();

        $save = Enregistrement::where('user_id', $user->id)
            ->where('post_id', $postId)
            ->first();

        if (!$save) {
            return response()->json(['message' => 'Not found']);
        }

        $save->delete();

        return response()->json(['message' => 'Unsaved']);
    }
}
