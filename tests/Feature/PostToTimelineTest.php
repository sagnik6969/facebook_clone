<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostToTimelineTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

    }

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
                'body' => 'Testing Body'
            ]);

        $post = Post::first();

        $this->assertCount(1, Post::all());

        $this->assertEquals($user->id, $post->user->id);
        $this->assertEquals('Testing Body', $post->body);

        // $response->dump();

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'type' => 'posts',
                    'post_id' => $post->id,
                    'attributes' => [
                        'posted_by' => [
                            'data' => [
                                'attributes' => [
                                    'name' => $user->name
                                ]
                            ]
                        ],
                        'body' => 'Testing Body'
                    ]
                ],
                'links' => [
                    'self' => url('/posts/' . $post->id)
                ]
            ]);
        // expected status code is mentioned in https://jsonapi.org/

    }

    public function test_a_user_can_create_a_text_post_with_an_image()
    {
        $this->withoutExceptionHandling();
        // the above code makes the error much clearer
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('user-image.jpg');

        $response = $this->actingAs(
            $user,
            'api'
        )
            ->post('/api/posts', [
                'body' => 'Testing Body',
                'image' => $file,
                'width' => 100,
                'height' => 100,
            ]);

        $response->assertStatus(201);

        Storage::disk('public')
            ->assertExists('post-images/' . $file->hashName());


        $response->assertStatus(201)
            ->assertJson([
                'data' => [

                    'attributes' => [
                        'body' => 'Testing Body',
                        'image' => url('storage/post-images/' . $file->hashName())

                    ]
                ],
            ]);

    }
}
