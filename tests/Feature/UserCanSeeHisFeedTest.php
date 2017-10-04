<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;
use Thoughts\Follower;
use Thoughts\Thought;
use Thoughts\User;

/**
 * Test to see if user can se his feed of thoughts of users he follows.
 *
 * @package Tests\Feature
 */
class UserCanSeeHisFeedTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function user_need_to_be_loged_in_to_see_his_feed()
    {

        $this->withExceptionHandling()->getJson('api/v1/feed')->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function can_see_feed()
    {

        $user = factory(User::class)->create();

        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();

        $thoughtA = factory(Thought::class)->create(['user_id' => $userA->id, 'created_at' => '2016-10-04 15:22:12']);
        $thoughtB = factory(Thought::class)->create(['user_id' => $userB->id, 'created_at' => '2017-10-04 15:10:12']);

        factory(Follower::class)->create(['follower_id' => $user->id, 'followed_id' => $userA->id]);
        factory(Follower::class)->create(['follower_id' => $user->id, 'followed_id' => $userB->id]);

        $response = $this->actingAs($user, 'api')->getJson('api/v1/feed');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(2, $response->json()['data']);

        $response->assertJson([
            'data' => [
                ['id' => $thoughtB->id, 'body' => $thoughtB->body, 'user' => ['id' => $userB->id]],
                ['id' => $thoughtA->id, 'body' => $thoughtA->body, 'user' => ['id' => $userA->id]]
            ],
        ]);

    }

    /** @test */
    public function feed_is_empty_when_user_do_not_have_follows()
    {

        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->getJson('api/v1/feed');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(0, $response->json()['data']);

    }

}
