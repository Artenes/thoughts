<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Thoughts\Http\Resources\UserResource;

/**
 * Pseudonym controller.
 *
 * @package Thoughts\Http\Controllers
 */
class PseudonymController extends Controller
{

    /**
     * Show a pseudonym for the authenticated user.
     *
     * @return UserResource
     */
    public function show()
    {

        $pseudonym = Auth::user()->pseudonym;

        return new UserResource($pseudonym);

    }

}
