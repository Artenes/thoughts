<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use LogicException;
use Thoughts\Follower;
use Thoughts\Http\Requests\StoreFollowerRequest;
use Thoughts\User;

/**
 * Followers controller.
 *
 * @package Thoughts\Http\Controllers
 */
class FollowersController extends Controller
{

    /**
     * Creates a new follower.
     *
     * @param StoreFollowerRequest $request
     * @return JsonResponse
     */
    public function store(StoreFollowerRequest $request)
    {

        $follower = Auth::user();
        $followed = User::findOrFail($request->get('user_id'));

        try {

            $follow = Follower::create($follower, $followed);

        } catch (LogicException $exception) {

            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);

        } catch (QueryException $exception) {

            if ($exception->getCode() != 23000)
                throw $exception;

            return response()->json('You cannot follow the same user twice', Response::HTTP_CONFLICT);

        }

        return response()->json($follow->toArray(), Response::HTTP_CREATED);

    }

}
