<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Thoughts\Like::class, function (Faker $faker) {

    return [
        'user_id' => function () {
            return factory(Thoughts\User::class)->create()->id;
        },
        'thought_id' => function () {
            return factory(Thoughts\Thought::class)->create()->id;
        },
    ];

});
