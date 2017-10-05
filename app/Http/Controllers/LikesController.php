<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Thoughts\Http\Requests\StoreLikeRequest;
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

        $user = $this->resolveUser($request->get('user'));

        $thoughts = $likes->findUserLikes($user, $request->get('s'));

        return new ThoughtsCollection($thoughts);

    }

    /**
     * Stores as thought like.
     *
     * @param StoreLikeRequest $request
     * @return JsonResponse
     */
    public function toggle(StoreLikeRequest $request)
    {

        $userId = Auth::user()->id;
        $thoughtId = $request->get('thought_id');

        $like = Like::where([['user_id', $userId], ['thought_id', $thoughtId]])->first();

        if ($like) {

            $like->delete();
            $status = Response::HTTP_OK;

        } else {

            $like = Like::create(['user_id' => $userId, 'thought_id' => $thoughtId]);
            $status = Response::HTTP_CREATED;

        }

        return response()->json($like->toArray(), $status);

    }

}
