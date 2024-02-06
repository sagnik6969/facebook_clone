<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Client\Response;
use App\Http\Resources\Post as PostResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class PostController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'data.attributes.body' => ''
        ]);
        $post = request()
            ->user()
            ->posts()
            ->create($data['data']['attributes']);

        return new PostResource($post);
    }
}
