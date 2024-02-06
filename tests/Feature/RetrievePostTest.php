<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RetrievePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_retrieve_post()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $posts = Post::factory(2)->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->get('/api/posts');

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $posts->first()->id,
                            'attributes' => [
                                'body' => $posts->first()->body
                            ]
                        ]
                    ],
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $posts->last()->id,
                            'attributes' => [
                                'body' => $posts->last()->body
                            ]
                        ]
                    ]

                ],
                'links' => [
                    'self' => url('/posts')
                ]
            ]);



    }
}
