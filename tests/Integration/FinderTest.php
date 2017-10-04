<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Thoughts\Finder;
use Thoughts\Searchable;

/**
 * Test for the finder to see if it can really fins things.
 *
 * @package Tests\Feature
 */
class FinderTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function user_can_search_for_thoughts()
    {

        $searchable = factory(Searchable::class)->create(['body' => 'My second thought about this.']);

        $finder = new Finder();

        $results = $finder->find('second thought about');

        $this->assertCount(1, $results);

        $this->assertEquals($searchable->body, $results->first()->body);

    }

    /** @test */
    public function user_can_search_for_thoughts_with_upper_case_letters()
    {

        factory(Searchable::class)->create(['body' => 'My second thought about this.']);

        $finder = new Finder();

        $results = $finder->find('SECOND ThouGht AbouT THIS');

        $this->assertCount(1, $results);

        $this->assertEquals('My second thought about this.', $results->first()->body);

    }

}
