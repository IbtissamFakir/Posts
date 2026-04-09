<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('statut', 'validated')
            ->with('user')
            ->latest()
            ->get();
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'content' => 'required',
            'user_id' => 'required',
            'images.*' => 'max:2048',
            'attachments.*' => 'file|max:10000',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('posts/images', 'public');
            }
        }

        $filePaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filePaths[] = $file->store('posts/files', 'public');
            }
        }

        $post = Post::create([
            'titre' => $request->titre,
            'content' => $request->content,
            'images' => $imagePaths,
            'fichiers' => $filePaths,
            'statut' => 'pending',
            'user_id' => $request->user_id,
            'date_publication' => now(),
        ]);

        return response()->json([
            'message' => 'Post créé avec succès !',
            'post' => $post
        ], 201);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $userId = auth()->id() ?? $post->user_id;

        if ($post->user_id != $userId) {
            return response()->json(['message' => 'la suppression interdite !'], 403);
        }

        if ($post->images && is_array($post->images)) {
            foreach ($post->images as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        if ($post->fichiers && is_array($post->fichiers)) {
            foreach ($post->fichiers as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $post->delete();
        return response()->json(['message' => 'Post supprimé avec succès.']);
    }
}

/* On retire user_id de la validation (car l'utilisateur ne doit
 plus l'envoyer manuellement) et on utilise auth()->id() pour le récupérer automatiquement depuis le badge (Token).*/
//namespace App\Http\Controllers\Api;
//
//use App\Http\Controllers\Controller;
//use App\Models\Post;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Storage;
//
//class PostController extends Controller
//{
//    public function index()
//    {
//        $posts = Post::where('statut', 'validated')
//            ->with('user') // Changé ici aussi
//            ->latest()
//            ->get();
//        return response()->json($posts);
//    }
//
//    public function store(Request $request)
//    {
//        // On retire user_id de la validation car on le détecte nous-mêmes
//        $request->validate([
//            'titre' => 'required|max:255',
//            'content' => 'required',
//            'images.*' => 'image|max:2048',
//            'attachments.*' => 'file|max:10000',
//        ]);
//
//        $imagePaths = [];
//        if ($request->hasFile('images')) {
//            foreach ($request->file('images') as $file) {
//                $imagePaths[] = $file->store('posts/images', 'public');
//            }
//        }
//
//        $filePaths = [];
//        if ($request->hasFile('attachments')) {
//            foreach ($request->file('attachments') as $file) {
//                $filePaths[] = $file->store('posts/files', 'public');
//            }
//        }
//
//        // On utilise auth()->id() pour l'utilisateur connecté
//        $post = Post::create([
//            'titre' => $request->titre,
//            'content' => $request->content,
//            'images' => $imagePaths,
//            'fichiers' => $filePaths,
//            'statut' => 'pending',
//            'user_id' => auth()->id(),
//            'date_publication' => now(),
//        ]);
//
//        return response()->json([
//            'message' => 'Post créé avec succès !',
//            'post' => $post
//        ], 201);
//    }
//
//    public function destroy($id)
//    {
//        $post = Post::findOrFail($id);
//
//        // 3. Sécurité : Seul l'auteur peut supprimer son post
//        if ($post->user_id != auth()->id()) {
//            return response()->json(['message' => 'Action interdite : vous n\'êtes pas l\'auteur !'], 403);
//        }
//
//        if ($post->images && is_array($post->images)) {
//            foreach ($post->images as $path) {
//                Storage::disk('public')->delete($path);
//            }
//        }
//
//        if ($post->fichiers && is_array($post->fichiers)) {
//            foreach ($post->fichiers as $path) {
//                Storage::disk('public')->delete($path);
//            }
//        }
//
//        $post->delete();
//        return response()->json(['message' => 'Post supprimé avec succès.']);
//    }
//}
