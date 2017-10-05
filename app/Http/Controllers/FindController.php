<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Http\Request;
use Thoughts\Finder;
use Thoughts\Http\Resources\SearchableCollection;
use Thoughts\Http\Resources\ThoughtsWithUserCollection;
use Thoughts\Thought;

/**
 * Controller to find resources.
 *
 * @package Thoughts\Http\Controllers
 */
class FindController extends Controller
{

    /**
     * Finds a resource.
     *
     * @param Request $request
     * @return SearchableCollection
     */
    public function find(Request $request)
    {

        $thoughts = (new Thought())->find($request->get('s'));

        return new ThoughtsWithUserCollection($thoughts);

    }

}
