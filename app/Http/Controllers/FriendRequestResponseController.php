<?php

namespace App\Http\Controllers;

use App\Exceptions\FriendRequestNotFoundException;
use App\Models\Friend;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FriendRequestResponseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'status' => 'required'
        ]);

        $userId = $request->user_id;
        $friendId = auth()->user()->id;

        try {
            $friendRequest = Friend::where('user_id', $userId)
                ->where('friend_id', $friendId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new FriendRequestNotFoundException();
        }

        $friendRequest->update([
            'confirmed_at' => now(),
            'status' => 1
        ]);

        return new \App\Http\Resources\Friend($friendRequest);
    }
}
