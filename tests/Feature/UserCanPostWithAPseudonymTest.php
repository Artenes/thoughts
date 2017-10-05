<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;
use Thoughts\User;

/**
 * Test to see if user can post a thought with a pseudonym.
 *
 * @package Tests\Feature
 */
class UserCanPostWithAPseudonymTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function can_post_as_pseudonym()
    {

        $user = factory(User::class)->create();
        $pseudonym = factory(User::class)->create(['real_id' => $user->id]);

        $response = $this->actingAs($user)->postJson('v1/thoughts', ['body' => 'My first thought about this', 'as_pseudonym' => true]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJson([
            'body' => 'My first thought about this',
            'user_id' => $pseudonym->id,
        ]);

    }

}
