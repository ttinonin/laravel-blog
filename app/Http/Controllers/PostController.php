<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function search($term) {
        // O driver do scout possibilita chamar toSearchableArray por search
        // Referenciando o modelo Post
       $posts = Post::search($term)->get();

       $posts->load('user:id,username,avatar');

       return $posts;
    }

    public function delete(Post $post) {
        if(auth()->user()->cannot('delete', $post)) {
            return 'You cannot do that';
        }
        $post->delete();

        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post deleted');
    }

    public function viewSinglePost(Post $post) {
        
        
        $ourHTML = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h3><br>');
        
        $post['body'] = $ourHTML;

        return view('single-post', ['post' => $post]);
    }

    public function showCreateForm() {
        return view('create-post');
    }

    public function storeNewPost(Request $request) {
        $incomingFields = $request->validate([
            "title" => 'required',
            "body" => 'required',
        ]);
        
        $incomingFields["title"] = strip_tags($incomingFields["title"]);
        $incomingFields["body"] = strip_tags($incomingFields["body"]);
        $incomingFields["user_id"] = auth()->id();

        $newPost = Post::create($incomingFields);

        return redirect("/post/{$newPost->id}")->with('success', 'Post created!');
    }
}
