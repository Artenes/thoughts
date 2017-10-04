<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Thoughts\Http\Resources\LikedThoughtsCollection;
use Thoughts\Like;
use Thoughts\User;

/**
 * Likes controller.
 *
 * @package Thoughts\Http\Controllers
 */
class LikesController extends Controller
{

    /**
     * Searches for the likes of an user.
     *
     * @param Request $request
     * @return LikedThoughtsCollection
     */
    public function find(Request $request)
    {

        $likes = new Like();

        $user = $this->resolverUser($request->get('user'));

        $thoughts = $likes->findUserLikes($user, $request->get('s'));

        return new LikedThoughtsCollection($thoughts);

    }

    /**
     * Resolve which user to use to search for likes.
     *
     * @param $userId
     * @return User
     * @throws ModelNotFoundException
     */
    protected function resolverUser($userId)
    {

        $user = User::find($userId) ?: Auth::user();

        if($user === null)
            throw new ModelNotFoundException();

        return $user;

    }

}
