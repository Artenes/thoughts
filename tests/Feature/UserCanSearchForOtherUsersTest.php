<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;
use Thoughts\Searchable;
use Thoughts\Thought;
use Thoughts\User;

/**
 * Test to see if user can search for thoughts.
 *
 * @package Tests\Feature
 */
class UserCanSearchForOtherUsersTest extends TestCase
{

    use DatabaseMigrations;

    protected function setUp()
    {

        $this->markTestSkipped('For now user will not be able to search for other users');

    }

    /** @test */
    public function user_dont_need_to_be_authenticated_to_search()
    {

        $response = $this->getJson('v1/find');

        $response->assertStatus(Response::HTTP_OK);

    }

    /** @test */
    public function user_finds_another_user_that_exists()
    {

        $user = factory(User::class)->create(['name' => 'Jhon Doe']);

        (new Searchable())->indexResource($user);

        $response = $this->getJson('v1/find?s=jhon doe');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(1, $response->json()['data']);

        $response->assertJson([
            'data' => [
                ['attributes' => ['name' => $user->name], 'resource' => ['id' => $user->id]]
            ]
        ]);

    }

    /** @test */
    public function user_dont_find_not_indexed_users()
    {

        factory(User::class)->create(['name' => 'Jhon Doe']);

        $response = $this->getJson('v1/find?s=jhon doe');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(0, $response->json()['data']);

    }

    /** @test */
    public function user_dont_find_a_user_that_does_not_exists()
    {

        $response = $this->getJson('v1/find?s=jhon doe');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(0, $response->json()['data']);

    }

    /** @test */
    public function user_finds_all_users_when_dont_provide_search_query()
    {

        $users = factory(Thought::class, 10)->create();
        $searchable = new Searchable();

        $users->each(function ($thought) use ($searchable) {
            $searchable->indexResource($thought);
        });

        $response = $this->getJson('v1/find');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(10, $response->json()['data']);

    }

}
