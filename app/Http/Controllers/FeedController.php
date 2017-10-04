<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Thoughts\Http\Resources\ThoughtsWithUserCollection;
use Thoughts\Thought;

/**
 * Feed controller.
 *
 * @package Thoughts\Http\Controllers
 */
class FeedController extends Controller
{

    /**
     * Shows the user feed.
     *
     * @return ThoughtsWithUserCollection
     */
    public function show()
    {

        $thoughts = (new Thought())->getUserFeed(Auth::user());

        return new ThoughtsWithUserCollection($thoughts);

    }

}
