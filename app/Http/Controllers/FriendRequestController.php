<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

        try {
            User::findOrFail($friendId)->friends()->attach(auth()->user());

        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }



        return new \App\Http\Resources\Friend(
            Friend::where('user_id', auth()
                ->user()->id)
                ->where('friend_id', $friendId)
                ->first()
        );


    }
}
