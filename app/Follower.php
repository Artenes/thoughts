<?php

namespace Thoughts;

use Illuminate\Database\Eloquent\Model;
use LogicException;

/**
 * A user that follows another user.
 *
 * @package Thoughts
 */
class Follower extends Model
{

    /** @var array  */
    protected $guarded = [];

    /**
     * Creates a new following association.
     *
     * @param User $follower
     * @param User $followed
     * @return Follower
     */
    public static function create(User $follower, User $followed)
    {

        if($follower->id == $followed->id)
            throw new LogicException('You cannot follow yourself');

        if($followed->real_id == $follower->id)
            throw new LogicException('You cannot follow your pseudonym');

        if($follower->real_id == $followed->id)
            throw new LogicException('You cannot follow your real self');

        return (new Follower())->query()->create([
            'follower_id' => $follower->id,
            'followed_id' => $followed->id,
        ]);

    }

}
