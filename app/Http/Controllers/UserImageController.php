<?php

namespace App\Http\Controllers;

use App\Models\UserImage;
use Illuminate\Http\Request;

class UserImageController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'image' => '',
            'width' => '',
            'height' => '',
            'location' => ''
        ]);

        $path = $data['image']->store('user-images', 'public');

        $userImage = auth()->user()->images()->create([
            'path' => $path,
            'height' => $data['height'],
            'width' => $data['width'],
            'location' => $data['location']
        ]);


        return new \App\Http\Resources\UserImage($userImage);


    }
}
