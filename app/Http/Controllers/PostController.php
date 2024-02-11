<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Client\Response;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\PostCollection;
use App\Models\Friend;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class PostController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'body' => '',
            'image' => '',
            'width' => '',
            'height' => '',
        ]);

        if (isset($data['image'])) {

            $image = $data['image']->store('post-images', 'public');

        }

        $post = request()
            ->user()
            ->posts()
            ->create([
                'body' => $data['body'],
                'image' => $image ?? null,
            ]);

        // dump($image);
        return new PostResource($post);
    }

    public function index()
    {
        $friends = Friend::friendships();
        if ($friends->isEmpty())
            return new PostCollection(request()->user()->posts);

        return new PostCollection(
            Post::whereIn(
                'user_id',
                [...$friends->pluck('user_id'), ...$friends->pluck('friend_id')]
            )
                ->get()
        );
    }
}
