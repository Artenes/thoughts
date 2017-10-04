<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Http\Request;
use Thoughts\Http\Resources\ThoughtsCollection;
use Thoughts\Like;

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
     * @return ThoughtsCollection
     */
    public function find(Request $request)
    {

        $likes = new Like();

        $user = $this->resolverUser($request->get('user'));

        $thoughts = $likes->findUserLikes($user, $request->get('s'));

        return new ThoughtsCollection($thoughts);

    }

}
