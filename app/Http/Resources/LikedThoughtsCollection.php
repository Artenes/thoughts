<?php

namespace Thoughts\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Collection of thoughts liked by an user.
 *
 * @package Thoughts\Http\Resources
 */
class LikedThoughtsCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        return $this->collection->map(function ($thought) {

            return [
                'body' => $thought->body,
                'created_at' => $thought->created_at,
            ];

        })->toArray();

    }

}
