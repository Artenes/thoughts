<?php

namespace Thoughts\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $userId = Auth::check() ? Auth::user()->id : null;
        $following = Auth::check() ? Auth::user()->following->where('id', $this->id)->first() !== null : false;

        return [

            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'username' => $this->username,
                'followers' => $this->followers->count(),
                'avatar' => $this->avatar,
            ],
            'meta' => [
                'is_authenticated' => $userId === $this->id,
                'following' => $following,
            ]

        ];

    }

}
