<?php

namespace Tests\Feature;

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
        $response = $this->actingAs(
            User::factory()->create(),
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

        $post = \App\Models\Post::first();

        $response->assertStatus(201);
        // expected status code is mentioned in https://jsonapi.org/

    }
}
