<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;
use Thoughts\Searchable;
use Thoughts\Thought;

/**
 * Test to see if user can search for thoughts.
 *
 * @package Tests\Feature
 */
class UserCanSearchForThoughtsTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function user_dont_need_to_be_authenticated_to_search()
    {

        $response = $this->getJson('api/v1/find');

        $response->assertStatus(Response::HTTP_OK);

    }

    /** @test */
    public function user_finds_a_thought_that_exists()
    {

        $thought = factory(Thought::class)->create(['body' => 'This thought will be found']);

        (new Searchable())->indexResource($thought);

        $response = $this->getJson('api/v1/find?s=will be found');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(1, $response->json()['data']);

        $response->assertJson([
            'data' => [
                ['id' => $thought->id, 'body' => $thought->body]
            ]
        ]);

    }

    /** @test */
    public function user_dont_find_not_indexed_thoughts()
    {

        factory(Thought::class)->create(['body' => 'This thought will be found']);

        $response = $this->getJson('api/v1/find?s=will be found');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(0, $response->json()['data']);

    }

    /** @test */
    public function user_dont_find_a_thought_that_does_not_exists()
    {

        $response = $this->getJson('api/v1/find?s=will be found');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(0, $response->json()['data']);

    }

    /** @test */
    public function user_finds_all_thoughts_when_dont_provide_search_query()
    {

        $thoughts = factory(Thought::class, 10)->create();
        $searchable = new Searchable();

        $thoughts->each(function ($thought) use ($searchable) {
            $searchable->indexResource($thought);
        });

        $response = $this->getJson('api/v1/find');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(10, $response->json()['data']);

    }

}
