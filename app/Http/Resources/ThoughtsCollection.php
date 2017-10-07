<?php

namespace Thoughts\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

/**
 * Collection of thoughts liked by an user.
 *
 * @package Thoughts\Http\Resources
 */
class ThoughtsCollection extends ResourceCollection
{

    protected $withUser = false;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $userId = Auth::check() ? Auth::user()->id : null;

        return $this->collection->map(function ($thought) use ($userId) {

            return [
                'id' => $thought->id,
                'body' => $thought->body,
                'created_at' => $thought->created_at->diffForHumans(),
                'likes' => $thought->likes->count(),
                'user' => $this->when($this->withUser, [
                    'id' => $thought->user->id,
                    'name' => $thought->user->name,
                    'avatar' => $thought->user->avatar,
                    'username' => $thought->user->username,
                ]),
                'meta' => [
                    'was_liked' => $thought->likes->where('user_id', $userId)->first() !== null,
                ]
            ];

        })->toArray();

    }

    /**
     * Adds the creator of the thought.
     *
     * @param $choice
     * @return $this
     */
    public function withUser()
    {

        $this->withUser = true;

        return $this;

    }

}
