<?php

namespace Thoughts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Thoughts\Contracts\SearchableResource;

class User extends Authenticatable implements SearchableResource
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

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
