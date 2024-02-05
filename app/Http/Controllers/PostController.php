<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class PostController extends Controller
{
    public function store()
    {
        return response([], 201);
    }
}
