<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Exceptions\ValidationErrorException;
use App\Models\Friend;
use App\Models\User;
use \Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FriendRequestController extends Controller
{
    public function store(Request $request)
    {
        // throw new ValidationException('abc');

        // try {
        $request->validate([
            'friend_id' => 'required'
        ]);
        // we are not using try catch block because we are handling the exception in 
        // Handler.php

        // } catch (ValidationException $e) {
        //     throw new ValidationErrorException(json_encode($e->getMessage()));
        // }

        $friendId = $request->friend_id;

        // Friend::create([
        //     'user_id' => auth()->user()->id,
        //     'friend_id' => $friendId
        // ]);
        // the bellow code is equivalent to above code 
        // attach => Attach a model to the parent.

        try {
            User::findOrFail($friendId)->friends()
                ->syncWithoutDetaching(auth()->user());
            // checks if the records are already linked 
            // if they are not linked then only it adds them to the database.

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
