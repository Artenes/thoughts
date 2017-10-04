<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Http\Request;
use Thoughts\Finder;
use Thoughts\Http\Resources\SearchableCollection;

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

        $finder = new Finder();

        return new SearchableCollection($finder->find($request->get('s')));

    }

}
