<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;
use Thoughts\User;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Test to see if user can swap places with pseudonym.
 *
 * @package Tests\Feature
 */
class UserCanSwapPlacesWithPseudonymTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function user_need_to_be_loged_in_to_swap_with_his_pseudonym()
    {

        $this->withExceptionHandling()->postJson('v1/swap')->assertStatus(Response::HTTP_BAD_REQUEST);

    }

    /** @test */
    public function real_user_can_swap_with_pseudonym()
    {

        $user = factory(User::class)->create();

        $pseudonym = factory(User::class)->create(['real_id' => $user->id]);

        $response = $this->actingAs($user)->postJson('v1/swap')->assertStatus(Response::HTTP_OK);

        $token = $response->json()['token'];

        $authUser = JWTAuth::authenticate($token);

        $this->assertTrue($authUser->is($pseudonym));

    }

    /** @test */
    public function pseudonym_can_swap_with_real_self()
    {

        $user = factory(User::class)->create();

        $pseudonym = factory(User::class)->create(['real_id' => $user->id]);

        $response = $this->actingAs($pseudonym)->postJson('v1/swap')->assertStatus(Response::HTTP_OK);

        $token = $response->json()['token'];

        $authUser = JWTAuth::authenticate($token);

        $this->assertTrue($authUser->is($user));

    }

}
