<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
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

}
