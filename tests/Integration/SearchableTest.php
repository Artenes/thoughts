<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Thoughts\Searchable;
use Thoughts\Thought;

/**
 * Test for the searchable model.
 *
 * @package Tests\Feature
 */
class SearchableTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function finds_a_searchable_instance_from_database()
    {

        factory(Searchable::class)->create(['identifier' => 1, 'type' => Thought::class]);

        $searchable = (new Searchable())->getInstance(Thought::class, 1);

        $this->assertTrue($searchable->exists);

    }

    /** @test */
    public function creates_a_new_searchable_instance_when_one_is_not_found()
    {

        $searchable = (new Searchable())->getInstance(Thought::class, 1);

        $this->assertFalse($searchable->exists);

        $this->assertEquals(Thought::class, $searchable->type);
        $this->assertEquals(1, $searchable->identifier);

    }

    /** @test */
    public function it_indexes_a_resource()
    {

        $thought = factory(Thought::class)->create();

        $searchable = (new Searchable())->indexResource($thought);

        $this->assertInstanceOf(Searchable::class, $searchable);

        $this->assertDatabaseHas('searchables', [
            'identifier' => $thought->id,
            'type' => Thought::class,
            'body' => $thought->body
        ]);

    }

}
