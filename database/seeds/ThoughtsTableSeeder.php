<?php

use Illuminate\Database\Seeder;
use Thoughts\Follower;
use Thoughts\Like;
use Thoughts\Thought;
use Thoughts\User;

/**
 * Seeds thoughts in database.
 */
class ThoughtsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (range(1, 50) as $count) {

            factory(User::class)->create([
                'avatar' => rand_avatar()
            ]);

        }

        User::get()->each(function ($user) {

            factory(Thought::class, 5)->create(['user_id' => $user->id]);

        });

        Thought::get()->each(function ($thought) {

            $amountOfLikes = random_int(1, 100);

            User::inRandomOrder()->take($amountOfLikes)->get()->each(function ($user) use ($thought) {

                factory(Like::class)->create(['thought_id' => $thought->id, 'user_id' => $user->id]);

            });

        });

        User::get()->each(function ($followed) {

            $amountOfFollowers = random_int(1, 100);

            User::inRandomOrder()->take($amountOfFollowers)->get()->each(function ($follower) use ($followed) {

                if($follower->is($followed))
                    return;

                factory(Follower::class)->create(['follower_id' => $follower->id, 'followed_id' => $followed->id]);

            });

        });


    }

}
