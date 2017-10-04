<?php

namespace Thoughts\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Collection of searchable resources.
 *
 * @package Thoughts\Http\Resources
 */
class SearchableCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        return $this->collection->map(function ($searchable) {

            return $searchable->meta;

        })->toArray();

    }

}
