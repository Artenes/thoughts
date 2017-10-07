<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Thoughts\Http\Resources\UserResource;
use Thoughts\User;

/**
 * User controller.
 *
 * @package Thoughts\Http\Controllers
 */
class UsersController extends Controller
{

    /**
     * Show a user by its username.
     *
     * @param $username
     * @return UserResource
     * @throws ModelNotFoundException
     */
    public function show($username)
    {

        $user = User::with('followers')->where('username', $username)->firstOrFail();

        return new UserResource($user);

    }

}
