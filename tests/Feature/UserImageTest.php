<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_image_can_be_uploaded()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('user-image.jpg');
        // for above line of code to work uncomment `extension=gd` in php.ini

        $response = $this->actingAs($user, 'api')
            ->post('/api/user-images', [
                'image' => $file,
                'height' => 122,
                'width' => 200,
                'location' => 'cover'
            ]);

        $response->assertStatus(201);
        $userImage = UserImage::first();
        $this->assertNotNull($userImage);
        $this->assertEquals('user-images/' . $file->hashName(), $userImage->path);
        //$file->hashName() => returns the name of the file which will be used for storing => it is different from
        //name of the uploaded file 
        $this->assertEquals(122, $userImage->height);
        $this->assertEquals(200, $userImage->width);
        $this->assertEquals('cover', $userImage->location);
        $this->assertEquals($user->id, $userImage->user_id);

        $response->assertJson([
            'data' => [
                'type' => 'user-images',
                'user_image_id' => $userImage->id,
                'attributes' => [
                    'path' => url('storage/' . $userImage->path),
                    'width' => $userImage->width,
                    'height' => $userImage->height,
                    'location' => $userImage->location,
                ]
            ],
            'links' => [
                'self' => url('/users/' . $userImage->user_id),
            ]
        ]);
    }

    public function test_users_are_returned_with_their_images()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('user-image.jpg');

        $this->actingAs($user, 'api')
            ->post('/api/user-images', [
                'image' => $file,
                'height' => 122,
                'width' => 200,
                'location' => 'cover'
            ])->assertStatus(201);


        $this->actingAs($user, 'api')
            ->post('/api/user-images', [
                'image' => $file,
                'height' => 122,
                'width' => 200,
                'location' => 'profile'
            ])->assertStatus(201);

        $response = $response = $this
            ->actingAs($user, 'api')
            ->get('/api/users/' . $user->id);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'type' => 'users',
                'user_id' => $user->id,
                'attributes' => [
                    'name' => $user->name,
                    'cover_image' => [
                        'data' => [
                            'type' => 'user-images',
                            'attributes' => []
                        ],
                    ],
                    'profile_image' => [
                        'data' => [
                            'type' => 'user-images',
                            'attributes' => []
                        ],
                    ]
                ]
            ],
            'links' => [
                'self' => url('/users/' . $user->id),
            ]
        ]);


    }
}
