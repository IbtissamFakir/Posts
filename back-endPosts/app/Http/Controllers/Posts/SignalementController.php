<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Post;
use App\Models\Signalement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignalementController extends Controller
{
    /**
     * Signaler un commentaire (Simulé pour User ID 1)
     */
    public function signalerCommentaire(Request $request, $postId, $commentaireId)
    {
        // SIMULATION : On ignore Auth::check()
        $userIdTest = 1; // On force l'utilisateur à être l'ID 1

        // Validation de la description
        $validated = $request->validate([
            'description' => 'required',
        ]);

        // Vérifier que le post existe
        $post = Post::find($postId);
        if (!$post) {
            return response()->json(['message' => 'Post non trouvé.'], 404);
        }

        // Vérifier que le commentaire existe et appartient au post
        $commentaire = Commentaire::where('id', $commentaireId)
            ->where('post_id', $postId)
            ->first();

        if (!$commentaire) {
            return response()->json(['message' => 'Commentaire non trouvé pour ce post.'], 404);
        }

        // Vérifier que l'utilisateur ne signale pas son propre commentaire
        if ($commentaire->user_id === $userIdTest) {
            return response()->json([
                'message' => 'Vous ne pouvez pas signaler votre propre commentaire (ID 1).'
            ], 403);
        }

        // Vérifier si l'utilisateur a déjà signalé ce commentaire
        $signalementExistant = Signalement::where('commentaire_id', $commentaireId)
            ->where('user_id', $userIdTest)
            ->first();

        if ($signalementExistant) {
            return response()->json([
                'message' => 'Vous avez déjà signalé ce commentaire.'
            ], 422);
        }

        // Créer le signalement avec l'ID 1
        $signalement = Signalement::create([
            'description' => $validated['description'],
            'commentaire_id' => $commentaireId,
            'user_id' => $userIdTest,
        ]);

        $commentaire->verifierEtMasquer();
        $commentaire->refresh();

        return response()->json([
            'message' => 'Commentaire signalé avec succès par l\'utilisateur 1.',
            'signalement' => $signalement,
            'commentaire_masque' => $commentaire->estMasque(),
            'nombre_signalements' => $commentaire->signalements()->count(),
        ], 201);
    }

    /**
     * Afficher tous les commentaires signalés (Simulé Admin)
     */
    public function commentairesSignales()
    {
        // SIMULATION : On désactive la vérification admin
        // if (!Auth::check() || Auth::user()->role !== 'admin') { ... }

        $commentairesSignales = Commentaire::where('status', 'masque')
            ->with([
                'user:id,name,email',
                'post:id,titre,user_id',
                'post.user:id,name',
                'signalements.user:id,name,email',
            ])
            ->withCount('signalements')
            ->get()
            ->map(function ($commentaire) {
                return [
                    'id' => $commentaire->id,
                    'contenu' => $commentaire->content,
                    'proprietaire' => $commentaire->user,
                    'nombre_signalements' => $commentaire->signalements_count,
                ];
            });

        return response()->json([
            'commentaires_signales' => $commentairesSignales,
            'total' => $commentairesSignales->count(),
        ], 200);
    }

    /**
     * Valider la suppression (Simulé Admin)
     */
    public function validerSuppression($commentaireId)
    {
        // SIMULATION : On désactive la vérification admin

        $commentaire = Commentaire::find($commentaireId);
        if (!$commentaire) {
            return response()->json(['message' => 'Commentaire non trouvé.'], 404);
        }

        if ($commentaire->status !== 'masque') {
            return response()->json(['message' => 'Ce commentaire n\'est pas masqué.'], 422);
        }

        $commentaire->delete();

        return response()->json([
            'message' => 'Admin : Commentaire supprimé avec succès.',
        ], 200);
    }

    /**
     * Rejeter un signalement (Simulé Admin)
     */
    public function rejeterSignalement($commentaireId)
    {
        // SIMULATION : On désactive la vérification admin

        $commentaire = Commentaire::find($commentaireId);
        if (!$commentaire) {
            return response()->json(['message' => 'Commentaire non trouvé.'], 404);
        }

        $commentaire->status = 'visible';
        $commentaire->save();
        $commentaire->signalements()->delete();

        return response()->json([
            'message' => 'Admin : Signalement rejeté, commentaire démasqué.',
        ], 200);
    }
}


//Detection automatique de l'utilisateur connecté

//namespace App\Http\Controllers\Posts;
//
//use App\Http\Controllers\Controller;
//use App\Models\Commentaire;
//use App\Models\Post;
//use App\Models\Signalement;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//
//class SignalementController extends Controller
//{
//    /**
//     * Signaler un commentaire (Détection automatique de l'utilisateur)
//     */
//    public function signalerCommentaire(Request $request, $postId, $commentaireId)
//    {
//        //  Vérifier si l'utilisateur est connecté (détecté par le Token)
//        if (!Auth::check()) {
//            return response()->json(['message' => 'Vous devez être connecté.'], 401);
//        }
//
//        $userId = Auth::id(); // Récupère l'ID de l'utilisateur connecté automatiquement
//
//        $validated = $request->validate([
//            'description' => 'required|string',
//        ]);
//
//        $post = Post::find($postId);
//        if (!$post) {
//            return response()->json(['message' => 'Post non trouvé.'], 404);
//        }
//
//        $commentaire = Commentaire::where('id', $commentaireId)
//            ->where('post_id', $postId)
//            ->first();
//
//        if (!$commentaire) {
//            return response()->json(['message' => 'Commentaire non trouvé.'], 404);
//        }
//
//        // Vérifier que l'utilisateur ne signale pas son propre commentaire
//        if ($commentaire->user_id === $userId) {
//            return response()->json([
//                'message' => 'Vous ne pouvez pas signaler votre propre commentaire.'
//            ], 403);
//        }
//
//        //  Vérifier si l'utilisateur a déjà signalé ce commentaire
//        $signalementExistant = Signalement::where('commentaire_id', $commentaireId)
//            ->where('user_id', $userId)
//            ->first();
//
//        if ($signalementExistant) {
//            return response()->json(['message' => 'Vous avez déjà signalé ce commentaire.'], 422);
//        }
//
//        // 4. Créer le signalement avec l'ID réel
//        $signalement = Signalement::create([
//            'description' => $validated['description'],
//            'commentaire_id' => $commentaireId,
//            'user_id' => $userId,
//        ]);
//
//        $commentaire->verifierEtMasquer();
//        $commentaire->refresh();
//
//        return response()->json([
//            'message' => 'Commentaire signalé avec succès.',
//            'commentaire_masque' => $commentaire->estMasque(),
//            'nombre_signalements' => $commentaire->signalements()->count(),
//        ], 201);
//    }
//
//    /**
//     * Afficher tous les commentaires signalés (Réservé Admin)
//     */
//    public function commentairesSignales()
//    {
//        // Vérification du rôle admin (automatique via l'utilisateur connecté)
//        if (!Auth::check() || Auth::user()->role !== 'admin') {
//            return response()->json(['message' => 'Accès réservé aux administrateurs.'], 403);
//        }
//
//        $commentairesSignales = Commentaire::where('status', 'masque')
//            ->with(['user', 'post', 'signalements.user'])
//            ->withCount('signalements')
//            ->get();
//
//        return response()->json([
//            'commentaires_signales' => $commentairesSignales,
//            'total' => $commentairesSignales->count(),
//        ], 200);
//    }
//
//    /**
//     * Valider la suppression (Admin uniquement)
//     */
//    public function validerSuppression($commentaireId)
//    {
//        if (!Auth::check() || Auth::user()->role !== 'admin') {
//            return response()->json(['message' => 'Action non autorisée.'], 403);
//        }
//
//        $commentaire = Commentaire::find($commentaireId);
//        if (!$commentaire || $commentaire->status !== 'masque') {
//            return response()->json(['message' => 'Commentaire non éligible à la suppression.'], 422);
//        }
//
//        $commentaire->delete();
//
//        return response()->json(['message' => 'Commentaire supprimé par l\'administrateur.'], 200);
//    }
//
//    /**
//     * Rejeter un signalement (Admin uniquement)
//     */
//    public function rejeterSignalement($commentaireId)
//    {
//        if (!Auth::check() || Auth::user()->role !== 'admin') {
//            return response()->json(['message' => 'Action non autorisée.'], 403);
//        }
//
//        $commentaire = Commentaire::find($commentaireId);
//        if (!$commentaire) {
//            return response()->json(['message' => 'Commentaire non trouvé.'], 404);
//        }
//
//        $commentaire->status = 'visible';
//        $commentaire->save();
//        $commentaire->signalements()->delete();
//
//        return response()->json(['message' => 'Signalement rejeté et commentaire rétabli.'], 200);
//    }
//}
