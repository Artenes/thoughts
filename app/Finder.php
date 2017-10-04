<?php

namespace Thoughts;

use Illuminate\Pagination\LengthAwarePaginator;
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
     * @return LengthAwarePaginator
     */
    public function find($search)
    {

        $search = strtolower($search);

        return Searchable::where(DB::raw("lower(body)"), 'like', "%{$search}%")->paginate();

    }

}
