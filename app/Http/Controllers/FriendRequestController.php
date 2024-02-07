<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;

class FriendRequestController extends Controller
{
    public function store(Request $request)
    {
        $friendId = $request->friend_id;

        // Friend::create([
        //     'user_id' => auth()->user()->id,
        //     'friend_id' => $friendId
        // ]);
        // the bellow code is equivalent to above code 
        // attach => Attach a model to the parent.
        User::find($friendId)->friends()->attach(auth()->user());


        return new \App\Http\Resources\Friend(
            Friend::where('user_id', auth()
                ->user()->id)
                ->where('friend_id', $friendId)
                ->first()
        );


    }
}
