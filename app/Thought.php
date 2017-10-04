<?php

namespace Thoughts;

use Illuminate\Database\Eloquent\Model;

/**
 * A thought.
 *
 * @package Thoughts
 */
class Thought extends Model
{

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Post a new thought by an user.
     *
     * @param User $user
     * @return bool
     */
    public function postBy(User $user)
    {

        $this->user_id = $user->id;

        return $this->save();

    }

}
