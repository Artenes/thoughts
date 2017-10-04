<?php

namespace Tests\Integration;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Thoughts\Like;
use Thoughts\Thought;
use Thoughts\User;

/**
 * Test for the like model.
 *
 * @package Tests\Integration
 */
class LikeTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function it_can_search_for_a_user_likes()
    {

        $user = factory(User::class)->create();

        $thought = factory(Thought::class)->create(['body' => 'Thought to find', 'user_id' => $user->id]);
        factory(Like::class)->create(['user_id' => $user->id, 'thought_id' => $thought->id]);
        factory(Like::class, 20)->create(['user_id' => $user->id]);

        $likes = new Like();
        $thoughts = $likes->findUserLikes($user, 'thought to find');

        $this->assertCount(1, $thoughts);
        $this->assertEquals($thought->id, $thoughts->first()->id);

    }

    /** @test */
    public function shows_all_likes_if_query_string_is_empty()
    {

        $user = factory(User::class)->create();
        factory(Like::class, 15)->create(['user_id' => $user->id]);

        $likes = new Like();
        $thoughts = $likes->findUserLikes($user);

        $this->assertCount(15, $thoughts);

    }

    /** @test */
    public function an_user_can_not_like_the_same_thought_twice()
    {

        $this->expectException(QueryException::class);
        $this->expectExceptionCode(23000);

        $user = factory(User::class)->create();
        $thought = factory(Thought::class)->create();

        Like::create(['user_id' => $user->id, 'thought_id' => $thought]);
        Like::create(['user_id' => $user->id, 'thought_id' => $thought]);

    }

}
