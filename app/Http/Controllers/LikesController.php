<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Database\QueryException;
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

        $user = $this->resolverUser($request->get('user'));

        $thoughts = $likes->findUserLikes($user, $request->get('s'));

        return new ThoughtsCollection($thoughts);

    }

    /**
     * Stores as thought like.
     *
     * @param StoreLikeRequest $request
     * @return JsonResponse
     */
    public function store(StoreLikeRequest $request)
    {

        try {

            $like = Like::create([
                'user_id' => Auth::user()->id,
                'thought_id' => $request->get('thought_id'),
            ]);

        } catch (QueryException $exception) {

            if ($exception->getCode() != 23000)
                throw $exception;

            return response()->json('You cannot like the same thought twice', Response::HTTP_CONFLICT);

        }

        return response()->json($like->toArray(), Response::HTTP_CREATED);

    }

}
