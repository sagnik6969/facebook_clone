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
                'post_id' => $this->id,
                'attributes' => [
                    'posted_by' => new UserResource($this->user),
                    'body' => $this->body,
                    'posted_at' => $this->created_at->diffForHumans(),
                    'image' => $this->image,
                    'likes' => new LikeCollection($this->likes),
                    'comments' => new CommentCollection($this->comments)
                ]
            ],
            'links' => [
                'self' => url('/posts/' . $this->id)
            ]
        ];
    }
}
