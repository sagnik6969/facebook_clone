<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAuthUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_be_fetched()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user, 'api')
            ->get('/api/auth-user');

        $response
            ->assertStatus(200)
            ->assertJson([

                'data' => [
                    'type' => 'users',
                    'user_id' => $user->id,
                    'attributes' => [
                        'name' => $user->name,
                    ]
                ],
                'links' => [
                    'self' => url('/users/' . $user->id),
                ]

            ]);
    }


}
