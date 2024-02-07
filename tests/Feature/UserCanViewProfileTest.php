<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCanViewProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_view_user_profile()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user, 'api')
            ->get('/api/users/' . $user->id);

        $response->assertStatus(200);
        $response->assertJson([
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
