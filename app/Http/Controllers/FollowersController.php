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
        $status = Response::HTTP_OK;

        $follow = Follower::where([
            ['follower_id', $follower->id],
            ['followed_id', $followed->id]
        ])->first();

        if($follow) {

            $follow->delete();

        } else {

            try {

                $follow = Follower::create($follower, $followed);
                $status = Response::HTTP_CREATED;

            } catch (LogicException $exception) {

                return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);

            }

        }

        return response()->json($follow->toArray(), $status);

    }

}
