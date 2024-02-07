<?php

namespace Tests\Feature;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FriendsTest extends TestCase
{
    use RefreshDatabase;

    function test_a_user_can_send_a_friend_request()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $friend = User::factory()->create();

        $response = $this
            ->actingAs($user, 'api')
            ->post('/api/friend-request', [
                'friend_id' => $friend->id
            ]);

        $response->assertStatus(200);

        $friendsRequest = Friend::first();

        $this->assertNotNull($friendsRequest);
        $this->assertEquals($friend->id, $friendsRequest->friend_id);
        $this->assertEquals($user->id, $friendsRequest->user_id);

        $response->assertJson([
            'data' => [
                'type' => 'friend-request',
                'friend_request_id' => $friendsRequest->id,
                'attributes' => [
                    'confirmed_at' => $friendsRequest->confirmed_at,
                ]
            ],
            'links' => [
                'self' => url('/users/' . $friendsRequest->friend_id),
            ]
        ]);

        // 

    }
}
