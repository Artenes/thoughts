<?php

namespace Thoughts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Thoughts\Contracts\SearchableResource;

/**
 * A thinker.
 *
 * @package Thoughts
 */
class User extends Authenticatable implements SearchableResource
{

    use Notifiable;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The user's thoughts.
     *
     * @return HasMany
     */
    public function thoughts()
    {

        return $this->hasMany(Thought::class);

    }

    /**
     * The user's pseudonym.
     *
     * @return HasOne
     */
    public function pseudonym()
    {

        return $this->hasOne(User::class, 'real_id');

    }

    /**
     * The user's he is following.
     *
     * @return BelongsToMany
     */
    public function following()
    {

        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id');

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

        return $this->name;

    }

    /**
     * @return array
     */
    public function indexMeta()
    {

        return [
            'name' => $this->name,
            'avatar' => $this->avatar,
        ];

    }

}
