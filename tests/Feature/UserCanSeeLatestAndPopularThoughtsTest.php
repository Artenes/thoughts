<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;
use Thoughts\Like;
use Thoughts\Thought;

/**
 * Test to see if user can se his feed of thoughts of users he follows.
 *
 * @package Tests\Feature
 */
class UserCanSeeLatestAndPopularThoughtsTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function user_do_not_need_to_be_loged_in_to_see_thoughts()
    {

        $this->withExceptionHandling()->getJson('v1/thoughts/latest')->assertStatus(Response::HTTP_OK);
        $this->withExceptionHandling()->getJson('v1/thoughts/popular')->assertStatus(Response::HTTP_OK);

    }

    /** @test */
    public function can_see_latest_thoughts()
    {

        $thoughtA = factory(Thought::class)->create(['created_at' => '2016-10-04 15:22:12']);
        $thoughtB = factory(Thought::class)->create(['created_at' => '2015-10-04 15:10:12']);
        $thoughtC = factory(Thought::class)->create(['created_at' => '2017-10-04 15:10:12']);

        $response = $this->getJson('v1/thoughts/latest');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(3, $response->json()['data']);

        $response->assertJson([
            'data' => [
                ['id' => $thoughtC->id, 'body' => $thoughtC->body],
                ['id' => $thoughtA->id, 'body' => $thoughtA->body],
                ['id' => $thoughtB->id, 'body' => $thoughtB->body]
            ],
        ]);

    }

    /** @test */
    public function can_see_popular_thoughts()
    {

        $thoughtA = factory(Thought::class)->create();
        $thoughtB = factory(Thought::class)->create();
        $thoughtC = factory(Thought::class)->create();

        factory(Like::class, 50)->create(['thought_id' => $thoughtB->id]);
        factory(Like::class, 23)->create(['thought_id' => $thoughtA->id]);
        factory(Like::class, 100)->create(['thought_id' => $thoughtC->id]);

        $response = $this->getJson('v1/thoughts/popular');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(3, $response->json()['data']);

        $response->assertJson([
            'data' => [
                ['id' => $thoughtC->id, 'body' => $thoughtC->body],
                ['id' => $thoughtB->id, 'body' => $thoughtB->body],
                ['id' => $thoughtA->id, 'body' => $thoughtA->body]
            ],
        ]);

    }

}
