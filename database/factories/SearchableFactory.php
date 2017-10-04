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

$factory->define(Thoughts\Searchable::class, function (Faker $faker) {

    $body = $faker->text(255);

    return [
        'body' => $body,
        'meta' => '{"body": "'.$body.'"}',
        'type' => Thoughts\Thought::class,
        'identifier' => function () {

            return factory(Thoughts\Thought::class)->create()->id;

        },
    ];
});
