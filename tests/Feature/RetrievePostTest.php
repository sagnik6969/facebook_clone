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
                            'post_id' => $posts->last()->id,
                            'attributes' => [
                                'body' => $posts->last()->body,
                                'posted_at' => $posts->last()->created_at->diffForHumans(),
                                'image' => $posts->last()->image,
                            ]
                        ]
                    ],
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $posts->first()->id,
                            'attributes' => [
                                'body' => $posts->first()->body,
                                'posted_at' => $posts->first()->created_at->diffForHumans(),
                                'image' => $posts->first()->image,

                            ]
                        ]
                    ]

                ],
                'links' => [
                    'self' => url('/posts')
                ]
            ]);
    }

    public function test_a_user_can_only_retrieve_his_own_post()
    {
        $this->withoutExceptionHandling();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user1->id
        ]);

        $response = $this
            ->actingAs($user2, 'api')
            ->get('/api/posts');

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [],
                'links' => [
                    'self' => url('/posts')
                ]
            ]);

        // assert json just checks the structure
        // to exactly match the json => this->assertExactJson
    }
}
