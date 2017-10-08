<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;
use Thoughts\User;

/**
 * Test to see if user can se his pseudonym profule.
 *
 * @package Tests\Feature
 */
class UserCanSeeHisPseudonymProfileTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function user_need_to_be_loged_in_to_see_his_pseudonym()
    {

        $this->withExceptionHandling()->getJson('v1/pseudonym')->assertStatus(Response::HTTP_BAD_REQUEST);

    }

    /** @test */
    public function can_see_pseudonym()
    {

        $user = factory(User::class)->create();

        $pseudonym = factory(User::class)->create(['real_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('v1/pseudonym');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'data' => [
                'id' => $pseudonym->id,
                'name' => $pseudonym->name,
                'username' => $pseudonym->username,
                'followers' => 0,
                'avatar' => $pseudonym->avatar,
            ],
            'meta' => [
                'is_authenticated' => true,
                'following' => false,
            ]
        ]);

    }

    

}
