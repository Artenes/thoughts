<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base test case class.
 *
 * @package Tests
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Set up some things for all tests.
     */
    protected function setUp()
    {

        parent::setUp();

        $this->withoutExceptionHandling();

    }

}
