<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Models\Post;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function store(Post $post)
    {
        $data = request()->validate([
            'body' => ''
        ]);

        $post->comments()->create([
            ...$data,
            'user_id' => auth()->user()->id
        ]);

        return new CommentCollection($post->comments);

    }
}
