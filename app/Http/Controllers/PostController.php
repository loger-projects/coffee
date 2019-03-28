<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use Illuminate\Http\Request;
use Route;

class PostController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return view('post.show');
    }
    /**
     * Undocumented function
     *
     * @param [type] $number
     * @return void
     */
    public function apiLatestPosts($number)
    {
        return Post::orderBy('created_at', 'desc')->skip(0)->take($number)->get()->toArray();
    }

    /**
     * Undocumented function
     *
     * @param [type] $slug
     * @return void
     */
    public function apiShow($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $post->comment_count = PostComment::where('post_id', $post->id)->count();
        return $post;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function apiComments($id)
    {
        $comments = PostComment::where('post_id', $id, 'and')->where('parent_id', null)->get();

        foreach($comments as $comment) {
            // get the user info
            $user = User::where('id', $comment->user_id)->first();
            $comment->user = $user;
            
            // get child comments
            $comment->child_comment = PostComment::where('parent_id', $comment->id)->get();

            foreach($comment->child_comment as $child) {
                // get user info of child
                $user = User::where('id', $child->user_id)->first();
                $child->user = $user;
                
                // get level 2 child comment
                $child->child_comment = PostComment::where('parent_id', $child->id)->get();
            }
        }
        
        return $comments;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @param [type] $cateID
     * @param [type] $amount
     * @return void
     */
    public function apiRelated($id, $cateID, $amount)
    {
        $posts = Post::where('cate_id', $cateID, 'and')->where('id', '!=', $id)->take($amount)->get();
        foreach($posts as $post) {
            $post->comment_count = PostComment::where('post_id', $post->id)->count();
            $post->author = User::where('id', $post->user_id)->first();
        }
        return $posts;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function routes()
    {
        Route::name('post.')->group(function() {
            Route::get('/posts', 'PostController@index')->name('index');
            Route::get('/post/{slug}', 'PostController@show')->name('show');
        });

        Route::group([
            'prefix' => 'api/post'
        ], function() {
            Route::name('api.post.')->group(function() {
                Route::get('/{number}/latest-posts', 'PostController@apiLatestPosts')->name('latestPosts');
                Route::get('/{slug}', 'PostController@apiShow')->name('show');
                Route::get('/{id}/comments', 'PostController@apiComments')->name('comments');
                Route::get('/{id}/category/{cateID}/related/{amount}', 'PostController@apiRelated')->name('related');
            });
        });
    }
}
