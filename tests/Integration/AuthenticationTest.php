<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Test to verify if authentication is working properly.
 *
 * @package Tests\Feature
 */
class AuthenticationTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function does_not_throw_invalid_token_exception_if_authentication_header_has_invalid_token()
    {

        $this->getJson('v1/thoughts', ['Authorization' => 'Bearer null'])->assertStatus(Response::HTTP_OK);

    }

}
