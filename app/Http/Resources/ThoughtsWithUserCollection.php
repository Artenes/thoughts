<?php

namespace Thoughts\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Collection of thoughts with user data.
 *
 * @package Thoughts\Http\Resources
 */
class ThoughtsWithUserCollection extends ResourceCollection
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
                'id' => $thought->id,
                'body' => $thought->body,
                'created_at' => $thought->created_at->diffForHumans(),
                'likes' => $thought->likes->count(),
                'user' => [
                    'id' => $thought->user->id,
                    'name' => $thought->user->name,
                    'avatar' => $thought->user->avatar,
                ],
            ];

        })->toArray();

    }

}
