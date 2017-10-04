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

    Route::get('find', 'FindController@find');

    Route::get('thoughts', 'ThoughtsController@find');
    Route::get('thoughts/{type}', 'ThoughtsController@index');

    Route::get('likes', 'LikesController@find');

    Route::group(['middleware' => 'auth:api'], function () {

        Route::post('thoughts', 'ThoughtsController@store');

        Route::post('likes', 'LikesController@store');

        Route::post('followers', 'FollowersController@store');

        Route::get('feed', 'FeedController@show');

    });

});
