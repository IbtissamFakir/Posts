<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle($postId)
    {
        $userId = auth()->id() ?? 1;

        $post = Post::findOrFail($postId);


        $like = Like::where('post_id', $postId)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {

            Like::create([
                'post_id' => $postId,
                'user_id' => $userId
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count() // On recompte les likes du post
        ]);
    }
}
