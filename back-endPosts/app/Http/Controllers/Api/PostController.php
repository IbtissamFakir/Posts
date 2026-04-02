<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Ajoute ça pour gérer la suppression du fichier

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('statut', 'validated')
            ->with('utilisateur')
            ->latest()
            ->get();
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|max:255', // Ta table accepte 255
            'content' => 'required',
            'image' => 'required|image'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create([
            'titre'            => $request->titre,
            'content'          => $request->content,
            'image'            => $imagePath,
            'statut'           => 'pending', // Par défaut comme dans ta table
            'utilisateur_id'   => $request->utilisateur_id,
            'date_publication' => now(), // <-- AJOUTE ÇA pour remplir la colonne datetime
        ]);

        return response()->json([
            'message' => 'Post créé avec succès !',
            'post'    => $post
        ], 201);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        $userId = auth()->id() ?? $post->utilisateur_id;

        if ($post->utilisateur_id != $userId) {
            return response()->json([
                'message' => 'la suppression interdite !'
            ], 403);
        }

        //  Supprimer le fichier image du dossier storage
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return response()->json(['message' => 'Post supprimé avec succès.']);
    }
}
