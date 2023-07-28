<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::group(['middleware'=> ['auth:sanctum']], function(){

    //User
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'userdetails']);
    Route::put('/user', [AuthController::class, 'update']);

    //Post 
    Route::get('/posts', [PostController::class, 'index']); //all posts
    Route::post('/posts', [PostController::class, 'store']);//create post
    Route::get('/posts/{id}', [PostController::class, 'show']);//get single post
    Route::put('/posts/{id}', [PostController::class, 'update']);//update post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);// delete pst 

    //Comment
    Route::get('/posts/{id}/comments', [CommentController::class, 'index']);//all comments of a post
    Route::post('/posts/{id}/comments', [CommentController::class, 'store']);// create comment on a post
    Route::put('/comments/{id}', [CommentController::class, 'update']);// update a comment
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);//delete a comment

    //Like
    Route::post('/posts/{id}/likes', [LikeController::class, 'likeOrUnlike']);//like or dislike a post
    

});
