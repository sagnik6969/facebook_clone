<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;

class FriendRequestResponseController extends Controller
{
    public function store(Request $request)
    {
        $userId = $request->user_id;
        $friendId = auth()->user()->id;

        $friendRequest = Friend::where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->firstOrFail();

        $friendRequest->update([
            'confirmed_at' => now(),
            'status' => 1
        ]);

        return new \App\Http\Resources\Friend($friendRequest);
    }
}
