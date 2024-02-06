<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostToTimelineTest extends TestCase
{
    use RefreshDatabase; // refreshes the database in every test
    /**
     * A basic feature test example.
     */
    public function test_a_user_can_create_a_text_post()
    {
        $this->withoutExceptionHandling();
        // the above code makes the error much clearer
        $user = User::factory()->create();
        $response = $this->actingAs(
            $user,
            'api'
        )
            ->post('/api/posts', [
                'data' => [
                    'type' => 'posts',
                    'attributes' => [
                        'body' => 'Testing Body'
                    ]
                ]
            ]);

        $post = Post::first();

        $this->assertCount(1, Post::all());

        $this->assertEquals($user->id, $post->user->id);
        $this->assertEquals('Testing Body', $post->body);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'type' => 'posts',
                    'post_id' => $post->id,
                    'attributes' => [
                        'body' => 'Testing Body'
                    ]
                ],
                'links' => [
                    'self' => url('/posts/' . $post->id)
                ]
            ]);
        // expected status code is mentioned in https://jsonapi.org/

    }
}
