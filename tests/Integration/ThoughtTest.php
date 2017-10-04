<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Thoughts\Follower;
use Thoughts\Searchable;
use Thoughts\Thought;
use Thoughts\User;

/**
 * Test for a thought.
 *
 * @package Tests\Feature
 */
class ThoughtTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function user_can_post_thought()
    {

        $user = factory(User::class)->create();

        $thought = new Thought(['body' => 'My first thought about this']);

        $this->assertTrue($thought->postBy($user));

        $this->assertDatabaseHas('thoughts', [
            'user_id' => $user->id,
            'body' => 'My first thought about this',
        ]);

    }

    /** @test */
    public function thought_can_be_indexed()
    {

        $thought = factory(Thought::class)->create();

        $searchable = new Searchable();

        $searchable->indexResource($thought);

        $this->assertDatabaseHas('searchables', [
            'identifier' => $thought->id,
            'type' => Thought::class,
            'body' => $thought->body
        ]);

    }

    /** @test */
    public function search_for_a_user_thoughts()
    {

        $user = factory(User::class)->create();
        $thought = factory(Thought::class)->create(['user_id' => $user->id, 'body' => 'This thought is peculiar']);
        factory(Thought::class, 10)->create(['user_id' => $user->id]);

        $thoughts = (new Thought())->findUserThoughts($user, 'peculiar');
        $this->assertCount(1, $thoughts);
        $this->assertEquals($thought->body, $thoughts->first()->body);

        $thoughts = (new Thought())->findUserThoughts($user);
        $this->assertCount(11, $thoughts);

    }

    /** @test */
    public function gets_an_user_feed()
    {

        $user = factory(User::class)->create();

        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();

        $thoughtA = factory(Thought::class)->create(['user_id' => $userA->id, 'created_at' => '2016-10-04 15:22:12']);
        $thoughtB = factory(Thought::class)->create(['user_id' => $userB->id, 'created_at' => '2017-10-04 15:10:12']);

        factory(Follower::class)->create(['follower_id' => $user->id, 'followed_id' => $userA->id]);
        factory(Follower::class)->create(['follower_id' => $user->id, 'followed_id' => $userB->id]);

        $thoughts = (new Thought())->getUserFeed($user);

        $this->assertCount(2, $thoughts);

        $this->assertTrue($thoughts->first()->is($thoughtB));
        $this->assertTrue($thoughts->last()->is($thoughtA));

    }

    /** @test */
    public function gets_an_empty_feed_if_user_do_not_has_follows()
    {

        $user = factory(User::class)->create();

        $thoughts = (new Thought())->getUserFeed($user);

        $this->assertCount(0, $thoughts);

    }

    /** @test */
    public function gets_an_empty_feed_if_user_follows_has_not_thoughts()
    {

        $user = factory(User::class)->create();

        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();

        factory(Follower::class)->create(['follower_id' => $user->id, 'followed_id' => $userA->id]);
        factory(Follower::class)->create(['follower_id' => $user->id, 'followed_id' => $userB->id]);

        $thoughts = (new Thought())->getUserFeed($user);

        $this->assertCount(0, $thoughts);

    }

}
