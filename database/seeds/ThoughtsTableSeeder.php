<?php

use Illuminate\Database\Seeder;
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

        factory(Thought::class, 50)->create();

    }

}
