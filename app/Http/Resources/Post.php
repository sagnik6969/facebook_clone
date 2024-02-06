<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;

class Post extends JsonResource
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
                'type' => 'posts',
                'posted_by' => new UserResource($this->user),
                'post_id' => $this->id,
                'attributes' => [
                    'body' => $this->body
                ]
            ],
            'links' => [
                'self' => url('/posts/' . $this->id)
            ]
        ];
    }
}
