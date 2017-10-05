<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;
use Thoughts\Thought;
use Thoughts\User;

/**
 * Test to see if user can like a thought.
 *
 * @package Tests\Feature
 */
class UserCanLikeAThoughtTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function user_need_to_be_loged_in_to_like_a_thought()
    {

        $this->withExceptionHandling()->postJson('v1/likes')->assertStatus(Response::HTTP_BAD_REQUEST);

    }

    /** @test */
    public function can_like_a_thought()
    {

        $user = factory(User::class)->create();
        $thought = factory(Thought::class)->create();

        $response = $this->actingAs($user)->postJson('v1/likes', [
            'thought_id' => $thought->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJson([
            'user_id' => $user->id,
            'thought_id' => $thought->id,
        ]);

    }

    /** @test */
    public function can_not_like_a_thought_that_does_not_exists()
    {

        $user = factory(User::class)->create();

        $this->actingAs($user)->withExceptionHandling()
            ->postJson('v1/likes', ['thought_id' => 0])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    /** @test */
    public function can_not_like_a_thought_twice()
    {

        $user = factory(User::class)->create();
        $thought = factory(Thought::class)->create();

        $this->actingAs($user)->postJson('v1/likes', [
            'thought_id' => $thought->id,
        ])->assertStatus(Response::HTTP_CREATED);

        $this->actingAs($user)->postJson('v1/likes', [
            'thought_id' => $thought->id,
        ])->assertStatus(Response::HTTP_CONFLICT);

    }

}
