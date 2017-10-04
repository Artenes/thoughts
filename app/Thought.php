<?php

namespace Thoughts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Thoughts\Contracts\SearchableResource;

/**
 * A thought.
 *
 * @package Thoughts
 */
class Thought extends Model implements SearchableResource
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

    /**
     * The user that created the thought.
     *
     * @return BelongsTo
     */
    public function user()
    {

        return $this->belongsTo(User::class);

    }

    /**
     * @return int
     */
    public function indexIdentifier()
    {

        return $this->id;

    }

    /**
     * @return string
     */
    public function indexType()
    {

        return static::class;

    }

    /**
     * @return string
     */
    public function indexBody()
    {

        return $this->body;

    }

    /**
     * @return array
     */
    public function indexMeta()
    {

        return [

            'id' => $this->id,
            'body' => $this->body,
            'user' => [
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
                'id' => $this->user->id,
            ]

        ];

    }

}
