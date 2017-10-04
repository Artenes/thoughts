<?php

namespace Thoughts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * A like for a thought.
 *
 * @package Thoughts
 */
class Like extends Model
{

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Search a user's likes.
     *
     * @param User $user
     * @param string $search
     * @return LengthAwarePaginator
     */
    public function findUserLikes(User $user, $search = '')
    {

        $search = strtolower($search);

        return Thought::whereHas('likes', function ($query) use ($user) {

            $query->where('user_id', $user->id);

        })->where(DB::raw('lower(body)'), 'like', "%{$search}%")->paginate()->appends(['s' => $search]);

    }

}
