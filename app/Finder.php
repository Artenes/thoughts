<?php

namespace Thoughts;

use Illuminate\Support\Facades\DB;

/**
 * Finds searcheable elements.
 *
 * @package Thoughts
 */
class Finder
{

    /**
     * Find an element.
     *
     * @param $search
     * @return mixed
     */
    public function find($search)
    {

        $search = strtolower($search);

        return Thought::where(DB::raw("lower(body)"), 'like', "%{$search}%")->get();

    }

}
