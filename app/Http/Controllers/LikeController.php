<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likeOrUnlike(Request $request, $id){
        $post = Post::find($id);

        if(!$post){
            return response([
                'message'=>'Post Not Found'
            ],403);
            
        }
        $like = $post->likes()->where('user_id', auth()->user()->id)->first();

        //if not liked, then like...
        if(!$like){
            $like = new Like();

            $like->post_id = $id;
            $like->user_id = auth()->user()->id;
            $like->save();

            return response([
                'message'=>'Liked'
            ],200);

        }

        $like->delete();
        
        return response([
            'message'=>'Disliked'
        ],200);



         
         
    }
}
