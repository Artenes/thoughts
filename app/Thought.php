<?php

namespace Thoughts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
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
     * The users likes for this thought.
     *
     * @return HasMany
     */
    public function likes()
    {

        return $this->hasMany(Like::class);

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

            'body' => $this->body,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
            ]

        ];

    }

    /**
     * Search for the thoughts of a user.
     *
     * @param User $user
     * @param string $search
     * @return LengthAwarePaginator
     */
    public function findUserThoughts(User $user, $search = '')
    {

        return $user->thoughts()
            ->where(DB::raw('lower(body)'), 'like', "%{$search}%")
            ->paginate()->appends(['s' => $search]);

    }

}
