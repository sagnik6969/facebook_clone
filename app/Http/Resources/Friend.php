<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Friend extends JsonResource
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
                'type' => 'friend-request',
                'friend_request_id' => $this->id,
                'attributes' => [
                    'confirmed_at' => optional($this->confirmed_at)->diffForHumans(),
                    'friend_id' => $this->friend_id,
                    'user_id' => $this->user_id
                    // optional similar to ? in js
                ]
            ],
            'links' => [
                'self' => url('/users/' . $this->friend_id),
            ]
        ];
    }
}
