<?php

namespace Tests\Feature;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
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
    }

    public function test_only_a_valid_user_can_be_friend()
    {
        // $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user, 'api')
            ->post('/api/friend-request', [
                'friend_id' => 1234
            ]);

        $response->assertStatus(404);

        $friendsRequest = Friend::first();
        $this->assertNull($friendsRequest);


        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'User Not Found',
                'detail' => 'Unable to locate the user with the given information.',
            ]
        ]);

    }

    public function test_friend_request_can_be_accepted()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $friend = User::factory()->create();

        $this
            ->actingAs($user, 'api')
            ->post('/api/friend-request', [
                'friend_id' => $friend->id
            ]);

        $response = $this
            ->actingAs($friend, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => $user->id,
                'status' => 1
            ]);

        $response->assertStatus(200);

        $friendsRequest = Friend::first();

        $this->assertNotNull($friendsRequest->confirmed_at);
        $this->assertInstanceOf(Carbon::class, $friendsRequest->confirmed_at);
        $this->assertEquals(now()->startOfSecond(), $friendsRequest->confirmed_at);
        //startOfSecond => Modify to start of current second, microseconds become 0
        $this->assertEquals($friend->id, $friendsRequest->friend_id);
        $this->assertEquals($user->id, $friendsRequest->user_id);
        $this->assertEquals(1, $friendsRequest->status);

        $response->assertJson([
            'data' => [
                'type' => 'friend-request',
                'friend_request_id' => $friendsRequest->id,
                'attributes' => [
                    'confirmed_at' => $friendsRequest->confirmed_at->diffForHumans(),
                ]
            ],
            'links' => [
                'self' => url('/users/' . $friendsRequest->friend_id),
            ]
        ]);

    }

    public function test_only_valid_requests_can_be_accepted()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => 123444,
                'status' => 1
            ]);

        $response->assertStatus(404);

        $friendsRequest = Friend::first();
        $this->assertNull($friendsRequest);


        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'Friend Not Found',
                'detail' => 'Unable to locate the friend with the given information.',
            ]
        ]);

    }

    public function test_only_the_recipient_can_accept_a_friends_request()
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();

        $this
            ->actingAs($user, 'api')
            ->post('/api/friend-request', [
                'friend_id' => $friend->id
            ])->assertStatus(200);

        $response = $this
            ->actingAs(User::factory()->create(), 'api')
            ->post('/api/friend-request-response', [
                'user_id' => $user->id,
                'status' => 1
            ])->assertStatus(404);


        $friendsRequest = Friend::first();
        $this->assertNull($friendsRequest->confirmed_at);
        $this->assertNull($friendsRequest->status);


        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'Friend Not Found',
                'detail' => 'Unable to locate the friend with the given information.',
            ]
        ]);

    }

    public function test_a_friend_is_required_for_friend_request()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user, 'api')
            ->postJson('/api/friend-request', [
            ])->assertStatus(422);


        $responseArray = json_decode($response->getContent(), true);
        // second argument true => returns an associative array
        $this->assertArrayHasKey('friend_id', $responseArray['errors']['meta']);
    }

    public function test_a_user_id_and_status_is_required_for_friend_request_response()
    {

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => '',
                'status' => ''
            ])->assertStatus(422);


        $responseArray = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('user_id', $responseArray['errors']['meta']);
        $this->assertArrayHasKey('status', $responseArray['errors']['meta']);

    }

    public function test_friendship_is_fetched_when_fetching_a_profile()
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $confirmedAt = now()->subDay();

        $friendRequest = Friend::create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'confirmed_at' => $confirmedAt,
            'status' => 1
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->get('/api/users/' . $friend->id)
            ->assertStatus(200);

        $response->assertJson([
            'data' => [

                'attributes' => [
                    'friendship' => [
                        'data' => [
                            'friend_request_id' => $friendRequest->id,
                            'attributes' => [
                                'confirmed_at' => $confirmedAt->diffForHumans(),
                            ]
                        ]

                    ]
                ]
            ],

        ]);

    }

    public function test_an_inverse_friendship_is_fetched_when_fetching_a_profile()
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $confirmedAt = now()->subDay();

        $friendRequest = Friend::create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'confirmed_at' => $confirmedAt,
            'status' => 1
        ]);

        $response = $this
            ->actingAs($friend, 'api')
            ->get('/api/users/' . $user->id)
            ->assertStatus(200);

        $response->assertJson([
            'data' => [

                'attributes' => [
                    'friendship' => [
                        'data' => [
                            'friend_request_id' => $friendRequest->id,
                            'attributes' => [
                                'confirmed_at' => $confirmedAt->diffForHumans(),
                            ]
                        ]

                    ]
                ]
            ],

        ]);

    }

    public function test_friend_request_can_be_ignored()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $friend = User::factory()->create();

        $this
            ->actingAs($user, 'api')
            ->post('/api/friend-request', [
                'friend_id' => $friend->id
            ]);

        $response = $this
            ->actingAs($friend, 'api')
            ->delete('/api/friend-request-response/delete', [
                'user_id' => $user->id,
            ])
            ->assertStatus(204);


        $friendsRequest = Friend::first();
        $this->assertNull($friendsRequest);

        $response->assertNoContent();

    }

    public function test_only_recipient_can_ignore_a_friend_request()
    {

        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $friend = User::factory()->create();

        $this
            ->actingAs($user, 'api')
            ->post('/api/friend-request', [
                'friend_id' => $friend->id
            ]);

        $response = $this
            ->actingAs($friend, 'api')
            ->delete('/api/friend-request-response/delete', [
                'user_id' => $user->id,
            ])
            ->assertStatus(204);


        $friendsRequest = Friend::first();
        $this->assertNull($friendsRequest);

        $response->assertNoContent();

    }

    public function test_only_the_recipient_can_ignore_a_friend_request()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $friend = User::factory()->create();

        $this
            ->actingAs($user, 'api')
            ->post('/api/friend-request', [
                'friend_id' => $friend->id
            ]);

        $response = $this
            ->actingAs(User::factory()->create(), 'api')
            ->delete('/api/friend-request-response/delete', [
                'user_id' => $user->id,
            ])
            ->assertStatus(404);


        $friendsRequest = Friend::first();
        $this->assertNotNull($friendsRequest);

        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'Friend Not Found',
                'detail' => 'Unable to locate the friend with the given information.',
            ]
        ]);

    }

}
