<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Thoughts\Http\Requests\StoreThoughtRequest;
use Thoughts\Searchable;
use Thoughts\Thought;

/**
 * Thoughts controller.
 *
 * @package Thoughts\Http\Controllers
 */
class ThoughtsController extends Controller
{

    /**
     * Store a new thought.
     *
     * @param StoreThoughtRequest $request
     * @return JsonResponse
     */
    public function store(StoreThoughtRequest $request)
    {

        $thought = new Thought(['body' => $request->get('body')]);

        $thought->postBy(Auth::user());

        (new Searchable())->indexResource($thought);

        return response()->json($thought->toArray(),Response::HTTP_CREATED);

    }

}
