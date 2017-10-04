<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
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

}
