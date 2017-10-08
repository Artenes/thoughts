<?php

namespace Thoughts;

use Faker\Factory;
use Faker\Generator;

/**
 * A user's pseudonym.
 *
 * @package Thoughts
 */
class Pseudonym
{

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * Pseudonym constructor.
     * @param $faker
     */
    public function __construct()
    {

        $this->faker = Factory::create();

    }

    /**
     * Creates a new pseudonym for a user.
     *
     * @param User $user
     */
    public function create(User $user)
    {

        User::create([

            'real_id' => $user->id,
            'name' => $this->faker->name,
            'username' => $this->faker->userName . str_random(3),
            'email' => $this->faker->safeEmail,
            'avatar' => asset('img/pseudonym.jpg')

        ]);

    }

}
