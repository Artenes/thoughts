<?php

use Illuminate\Database\Seeder;

/**
 * Seeds the database.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (config('app.env') !== 'production') {

            $this->call(ThoughtsTableSeeder::class);

        }

    }

}
