<?php

namespace App\Http\Resources;

use App\Models\Friend;
use App\Http\Resources\Friend as FriendResource;
use App\Http\Resources\UserImage as UserImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'type' => 'users',
                'user_id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'friendship' => new FriendResource(Friend::friendship($this->id)),
                    'cover_image' => new UserImageResource($this->coverImage),
                    'profile_image' => new UserImageResource($this->profileImage)
                ]
            ],
            'links' => [
                'self' => url('/users/' . $this->id),
            ]
        ];
    }
}
