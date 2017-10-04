<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;
use Thoughts\User;

/**
 * Test to see if user can post a thought.
 *
 * @package Tests\Feature
 */
class UserCanPostAThoughtTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function user_can_post_a_thought()
    {

        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->postJson('api/v1/thoughts', ['body' => 'My first thought about this']);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJson([
            'body' => 'My first thought about this',
            'user_id' => $user->id,
        ]);

    }

    /** @test */
    public function user_cannot_post_empty_thoughts()
    {

        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->withExceptionHandling()->postJson('api/v1/thoughts', ['body' => '']);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonMissing([
            'body' => 'My first thought about this',
            'user_id' => $user->id,
        ]);

    }

    /** @test */
    public function user_must_be_loged_in_to_post_thoughts()
    {

        $response = $this->withExceptionHandling()->postJson('api/v1/thoughts', ['body' => 'This is my thought']);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

}
