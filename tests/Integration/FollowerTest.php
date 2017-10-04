<?php

namespace Tests\Integration;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LogicException;
use Tests\TestCase;
use Thoughts\Follower;
use Thoughts\User;

/**
 * Test for the follower model.
 *
 * @package Tests\Feature
 */
class FollowerTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function a_user_can_not_follow_itself()
    {

        $this->expectException(LogicException::class);

        $user = factory(User::class)->create();

        Follower::create($user, $user);

    }

    /** @test */
    public function a_user_can_follow_another_user()
    {

        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        Follower::create($user, $anotherUser);

        $this->assertDatabaseHas('followers', [
            'follower_id' => $user->id,
            'followed_id' => $anotherUser->id,
        ]);

    }

    /** @test */
    public function a_user_can_not_follow_another_user_twice()
    {

        $this->expectException(QueryException::class);
        $this->expectExceptionCode(23000);

        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        Follower::create($user, $anotherUser);
        Follower::create($user, $anotherUser);

    }

    /** @test */
    public function a_user_can_not_follow_his_pseudonym()
    {

        $this->expectException(LogicException::class);

        $user = factory(User::class)->create();
        $pseudonym = factory(User::class)->create(['real_id' => $user->id]);

        Follower::create($user, $pseudonym);

    }

    /** @test */
    public function a_pseudonym_can_not_follow_his_real_self()
    {

        $this->expectException(LogicException::class);

        $user = factory(User::class)->create();
        $pseudonym = factory(User::class)->create(['real_id' => $user->id]);

        Follower::create($pseudonym, $user);

    }

}
