<?php

use Illuminate\Database\Seeder;
use Thoughts\Like;
use Thoughts\Searchable;
use Thoughts\Thought;

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

        $index = new Searchable();

        factory(Thought::class, 50)->create()->each(function ($thought) use ($index) {

            factory(Like::class, random_int(1, 100))->create(['thought_id' => $thought->id]);

            $index->indexResource($thought);

            $index->indexResource($thought->user);

        });

    }

}
