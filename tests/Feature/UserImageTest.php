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
        dump($file->hashName());
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
                    'path' => url($userImage->path),
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
}
