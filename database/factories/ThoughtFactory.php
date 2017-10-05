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

$factory->define(Thoughts\Thought::class, function (Faker $faker) {
    return [
        'body' => $faker->text(100),
        'user_id' => function () {

            return factory(Thoughts\User::class)->create()->id;

        }
    ];
});
