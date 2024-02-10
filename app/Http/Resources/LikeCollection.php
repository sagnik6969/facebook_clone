<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LikeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'like_count' => $this->count(),
            'user_likes_post' => $this->collection->contains('id', auth()->user()->id),
            'links' => [
                'self' => url('/posts'),
            ]
        ];
    }
}
