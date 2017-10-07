<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {

    Route::post('login', 'SocialAuthController@store');

    Route::get('find', 'FindController@find');

    Route::get('thoughts/user/{id?}', 'ThoughtsController@find');

    Route::get('thoughts/{filter?}', 'ThoughtsController@index');

    Route::get('likes', 'LikesController@find');

    Route::get('user/{username}', 'UsersController@show');

    Route::group(['middleware' => ['jwt.auth', 'jwt.refresh']], function () {

        Route::post('thoughts', 'ThoughtsController@store');

        Route::post('likes', 'LikesController@toggle');

        Route::post('followers', 'FollowersController@store');

        Route::get('feed', 'FeedController@show');

    });

});
