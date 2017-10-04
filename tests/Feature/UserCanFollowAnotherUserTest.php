<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;
use Thoughts\User;

/**
 * Test to see if user can follow anouther user (or a pseudonym).
 *
 * @package Tests\Feature
 */
class UserCanFollowAnotherUserTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function user_need_to_be_loged_in_to_follow_another_user()
    {

        $this->withExceptionHandling()->postJson('v1/followers')->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function can_follow_another_user()
    {

        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->postJson('v1/followers', [
            'user_id' => $anotherUser->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJson([
            'follower_id' => $user->id,
            'followed_id' => $anotherUser->id,
        ]);

    }

    /** @test */
    public function can_not_follow_a_user_that_does_not_exists()
    {

        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->withExceptionHandling()
            ->postJson('v1/likes', ['user_id' => 0])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    /** @test */
    public function can_not_follow_the_same_user_twice()
    {

        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $this->actingAs($user, 'api')->postJson('v1/followers', [
            'user_id' => $anotherUser->id,
        ])->assertStatus(Response::HTTP_CREATED);

        $this->actingAs($user, 'api')->postJson('v1/followers', [
            'user_id' => $anotherUser->id,
        ])->assertStatus(Response::HTTP_CONFLICT);

    }

    /** @test */
    public function can_not_follow_own_pseudonym()
    {

        $user = factory(User::class)->create();
        $pseudonym = factory(User::class)->create(['real_id' => $user->id]);

        $this->actingAs($user, 'api')->postJson('v1/followers', [
            'user_id' => $pseudonym->id,
        ])->assertStatus(Response::HTTP_BAD_REQUEST);

    }

    /** @test */
    public function can_not_follow_real_self_as_pseudonym()
    {

        $user = factory(User::class)->create();
        $pseudonym = factory(User::class)->create(['real_id' => $user->id]);

        $this->actingAs($pseudonym, 'api')->postJson('v1/followers', [
            'user_id' => $user->id,
        ])->assertStatus(Response::HTTP_BAD_REQUEST);

    }

}
