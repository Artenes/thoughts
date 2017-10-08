<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
    public function show($username = null)
    {

        if ($username) {

            $user = User::with('followers')->where('username', $username)->firstOrFail();

        } else {

            $user = Auth::user();

        }

        if(!$user)
            abort(Response::HTTP_NOT_FOUND, 'User not found');

        return new UserResource($user);

    }

}
