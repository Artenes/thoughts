<?php

namespace Thoughts\Http\Controllers;

use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Thoughts\Http\Requests\StoreSocialAuthRequest;
use Thoughts\Http\Resources\UserResource;
use Thoughts\Pseudonym;
use Thoughts\User;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Controller for social authentication.
 *
 * @package Thoughts\Http\Controllers
 */
class SocialAuthController extends Controller
{

    /**
     * Create a new user from a social login.
     *
     * @param StoreSocialAuthRequest $request
     */
    public function store(StoreSocialAuthRequest $request)
    {

        $user = $this->resolveSocialUser($request->get('service'), $request->get('token'));

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'user' => (new UserResource($user))->withPseudonym()->toArray(),
        ]);

    }

    /**
     * Resolve the user from the application base on social credentials.
     *
     * @param $service
     * @param $token
     * @return User
     */
    protected function resolveSocialUser($service, $token)
    {

        $socialUser = Socialite::driver($service)->userFromToken($token);

        $user = User::where("{$service}_id", $socialUser->id)->first();

        if (!$user)
            $user = User::where('email', $socialUser->email)->first();

        if (!$user)
            $user = new User();

        if (empty($socialUser->email)) {

            abort(Response::HTTP_BAD_REQUEST, 'Please allow us to retrieve your email');

        }

        $user->email = $socialUser->email;
        $user->name = $socialUser->name;
        $user->avatar = $socialUser->avatar;
        $user->{"{$service}_id"} = $socialUser->id;
        $user->username = slugify($user->name);
        $user->save();

        if ($user->pseudonym === null) {

            (new Pseudonym())->create($user);
            $user->load('pseudonym');

        }

        return $user;

    }

}
