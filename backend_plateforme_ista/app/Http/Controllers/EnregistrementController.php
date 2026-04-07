<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Enregistrement;
use Illuminate\Http\Request;

class EnregistrementController extends Controller
{
    public function save(Post $post)
    {
        $user = auth()->user();

        $enregistrement = Enregistrement::firstOrCreate([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        return response()->json([
            'message' => 'Post enregistré avec succès',
            'enregistrement' => $enregistrement
        ]);
    }

    public function unsave(Post $post)
    {
        $user = auth()->user();

        $deleted = Enregistrement::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->delete();

        return response()->json([
            'message' => $deleted ? 'Enregistrement supprimé' : 'Post non enregistré'
        ]);
    }
}
