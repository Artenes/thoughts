<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    /**
     * Authenticate the request with a jwt token created from the provided user.
     *
     * @param UserContract $user
     * @param null $driver
     * @return $this
     */
    public function actingAs(UserContract $user, $driver = null)
    {

        if($driver != null)
            return parent::actingAs($user, $driver);

        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', "Bearer $token");

        return $this;

    }

}
