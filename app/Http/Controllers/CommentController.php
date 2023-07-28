<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    // get all comments of a post...
    public function index($id){
        $post = Post::find($id);

        if(!$post){
            return response([
                'message'=>'Post Not Found'
            ],403);
        }

        return response([
            'post'=>$post->comments()->with('user:id,name,image')->get()
        ],200);
    }

    
    public function store(Request $request, $id){
        $post = Post::find($id);

        if(!$post){
            return response([
                'message'=>'Post Not Found'
            ],403);
        }

        $validator = Validator::make($request->all(),[
            'comment'=>'required|string'
        ]);

        $comment = new Comment();
        $comment->comment = $request->input('comment');
        $comment->user_id = auth()->user()->id;
        $comment->post_id = $id;
        $comment->save();

        return response([
            'message'=>'Comment created'
        ],200);
    }

    public function update(Request $request, $id){
        $comment = Comment::find($id);

        if(!$comment){
            return response([
                'message'=>'Comment Not Found'
            ],403);
        }

        $validator = Validator::make($request->all(),[
            'comment'=>'required|string'
        ]);

        $comment = new Comment();
        $comment->comment = $request->input('comment');
        $comment->update();

        return response([
            'message'=>'Comment updated'
        ],200);



    }

    public function destroy($id){
         $comment = Comment::find($id);

         if(!$comment){
            return response([
                'message'=>'Comment Not Found'
            ],403);
         }

         if($comment->user_id != auth()->user()->id){
            return response([
                'message'=>'Permission denied'
            ],403);
         }

         $comment->delete();

         return response([
            'message'=>'Comment deleted'
         ],200);
    }


}
