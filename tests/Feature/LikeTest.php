<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_like_a_post()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'id' => 123
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->post('/api/posts/' . $post->id . '/like');

        $response->assertStatus(200);
        $this->assertCount(1, $user->likedPosts);

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'likes',
                        // 'like_id' => 1,
                        'attributes' => []
                    ],
                    'links' => [
                        'self' => url('/posts/' . $post->id),
                    ]

                ]
            ],
            'like_count' => 1,
            'user_likes_post' => true,
            'links' => [
                'self' => url('/posts'),
            ]
        ]);

    }

    public function test_posts_are_returned_with_likes()
    {

        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'id' => 123
        ]);

        $this
            ->actingAs($user, 'api')
            ->post('/api/posts/' . $post->id . '/like')
            ->assertStatus(200);

        $response = $this->get('/api/posts');
        $response->assertStatus(200);
        $this->assertCount(1, $user->likedPosts);

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'attributes' => [
                            'likes' => [
                                'data' => [
                                    [
                                        'data' => [
                                            'type' => 'likes',
                                            // 'like_id' => 1,
                                            'attributes' => []
                                        ],
                                        'links' => [
                                            'self' => url('/posts/' . $post->id),
                                        ]

                                    ]
                                ],
                                'like_count' => 1,
                                'user_likes_post' => true,
                                'links' => [
                                    'self' => url('/posts'),
                                ]
                            ],
                        ]
                    ],

                ]
            ]
            ,
            'links' => [
                'self' => url('/posts')
            ]
        ]);


    }
}
