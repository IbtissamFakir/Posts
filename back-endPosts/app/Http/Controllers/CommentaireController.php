<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{
        //Affichage de la liste des commentaires
        public function index($postId)
        {
                $post = Post::find($postId);
                if (!$post) {
                        return response()->json(['error' => 'Post non trouvé'], 404);
                }
                $commentaires = Commentaire::where('post_id', $post->id)
                        ->with('user')
                        ->latest()
                        ->get();

                return response()->json($commentaires);
        }

        //Commenter un post
        public function store(Request $request, $postId)
    {
        $post=Post::findOrFail($postId);
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        $commentaire = Commentaire::create([
        'content' => $validated['content'],
        'user_id' => auth()->id() ?? 1,
        'post_id' => $post->id,
        'date_publication' => now(),
    ]);
        return response()->json($commentaire->load('user'), 201);
    }
    public function show($postId, $id)
    {
        return Commentaire::with('user')
            ->where('post_id', $postId)
            ->findOrFail($id);
    }
   // Modifier un commentaire
    public function update(Request $request, $postId, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $commentaire = Commentaire::where('post_id', $postId)
            ->where('id', $id)
            ->firstOrFail();

        // Empêcher quelqu’un de modifier le commentaire d’un autre
        //if ($commentaire->user_id !== auth()->id()) {
        //     return response()->json(['error' => 'Non autorisé'], 403);
        // }

        $commentaire->update([
            'content' => $validated['content'],
        ]);

        return response()->json($commentaire->load('user'));
    }

   
}
