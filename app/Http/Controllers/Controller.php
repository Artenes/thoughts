<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Thoughts\User;

/**
 * Base controller.
 *
 * @package Thoughts\Http\Controllers
 */
class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Resolve which user to use to search for something.
     *
     * @param $userId
     * @return User
     * @throws ModelNotFoundException
     */
    protected function resolverUser($userId)
    {

        $user = User::find($userId) ?: Auth::user();

        if ($user === null)
            throw new ModelNotFoundException('No user found');

        return $user;

    }

}
