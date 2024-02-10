<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostCommentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_comment_on_a_post()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'id' => 123,
            'user_id' => $user->id
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->post('/api/posts/' . $post->id . '/comment', [
                'body' => 'comment text'
            ]);

        $response->assertStatus(200);

        $this->assertCount(1, Comment::all());
        $comment = Comment::first();

        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);
        $this->assertEquals('comment text', $comment->body);

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'comments',
                        'comment_id' => 1,
                        'attributes' => [
                            'commented_by' => [
                                'data' => [
                                    'user_id' => $user->id,
                                    'attributes' => [
                                        'name' => $user->name,
                                    ]
                                ]
                            ],
                            'body' => 'comment text',
                            'commented_at' => $comment->created_at->diffForHumans(),
                        ]
                    ],
                    'links' => [
                        'self' => url('/posts/123'),
                    ]
                ]
            ],
            'links' => [
                'self' => url('/posts'),
            ]
        ]);

    }

    public function test_a_body_is_required_to_leave_a_comment_on_a_post()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'id' => 123,
            'user_id' => $user->id
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->post('/api/posts/' . $post->id . '/comment', [
                'body' => ''
            ]);

        $response->assertStatus(422);

        $responseArray = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('body', $responseArray['errors']['meta']);

    }

    public function test_posts_are_returned_with_comments()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'id' => 123,
            'user_id' => $user->id
        ]);

        $this
            ->actingAs($user, 'api')
            ->post('/api/posts/' . $post->id . '/comment', [
                'body' => 'comment text'
            ])
            ->assertStatus(200);

        $response = $this->get('/api/posts');
        $response->assertStatus(200);

        $comment = Comment::first();

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'attributes' => [
                            'comments' => [
                                'data' => [
                                    [
                                        'data' => [
                                            'type' => 'comments',
                                            'comment_id' => $comment->id,
                                            'attributes' => [
                                                'commented_by' => [
                                                    'data' => [
                                                        'user_id' => $user->id,
                                                        'attributes' => [
                                                            'name' => $user->name,
                                                        ]
                                                    ]
                                                ],
                                                'body' => 'comment text',
                                                'commented_at' => $comment->created_at->diffForHumans(),
                                            ]
                                        ],
                                        'links' => [
                                            'self' => url('/posts/123'),
                                        ]
                                    ]
                                ],
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
