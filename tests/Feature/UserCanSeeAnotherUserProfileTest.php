<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;
use Thoughts\Follower;
use Thoughts\User;

/**
 * Test to see if user can see other user profile.
 *
 * @package Tests\Feature
 */
class UserCanSeeAnotherUserProfileTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function can_see_other_user_profile()
    {

        $otherUser = factory(User::class)->create();

        factory(Follower::class, 102)->create(['followed_id' => $otherUser->id]);

        $response = $this->getJson("v1/user/{$otherUser->username}");

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'data' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'username' => $otherUser->username,
                'followers' => 102,
                'avatar' => $otherUser->avatar,
            ],
            'meta' => [
                'is_authenticated' => false,
            ]
        ]);

    }

    /** @test */
    public function checks_if_it_is_authenticated_user()
    {

        $user = factory(User::class)->create();
        $pseudonym = factory(User::class)->create(['real_id' => $user->id]);

        $response = $this->getJson("v1/user/{$user->username}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['meta' => ['is_authenticated' => false]]);

        $response = $this->getJson("v1/user/{$pseudonym->username}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['meta' => ['is_authenticated' => false]]);

        $response = $this->actingAs($user)->getJson("v1/user/{$user->username}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['meta' => ['is_authenticated' => true]]);

        $response = $this->actingAs($pseudonym)->getJson("v1/user/{$user->username}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['meta' => ['is_authenticated' => true]]);

        $response = $this->actingAs($user)->getJson("v1/user/{$pseudonym->username}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['meta' => ['is_authenticated' => true]]);

    }

    /** @test */
    public function returns_404_when_user_does_not_exist()
    {

        $this->withExceptionHandling()->getJson('v1/user/none')->assertStatus(Response::HTTP_NOT_FOUND);

    }

    /** @test */
    public function returns_authenticaded_user_data_when_no_username_is_provided_in_route()
    {

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->getJson('v1/user');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'followers' => 0,
                'avatar' => $user->avatar,
            ],
            'meta' => [
                'is_authenticated' => true,
            ]
        ]);

    }

    /** @test */
    public function returns_404_when_no_username_is_provided_and_no_user_is_authenticated()
    {

        $this->withExceptionHandling()->getJson('v1/user')->assertStatus(Response::HTTP_NOT_FOUND);

    }

}
