<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Thoughts\Http\Resources\ThoughtsCollection;
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
     * @return ThoughtsCollection
     */
    public function show()
    {

        $thoughts = (new Thought())->getUserFeed(Auth::user());

        return (new ThoughtsCollection($thoughts))->withUser();

    }

}
