<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Laravel\Socialite\AbstractUser as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Mockery as m;
use Tests\TestCase;
use Thoughts\User;

/**
 * Test to see if user can login using facebook.
 *
 * @package Tests\Feature
 */
class UserCanLoginWithFacebookTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function creates_new_user_in_first_login()
    {

        $this->mockSocialite('facebook', 'some-random-token', [
            'id' => 'facebook-id',
            'name' => 'Jhon Doe',
            'email' => 'jhon@doe.com',
            'avatar' => 'https://path.to/avatar',
        ]);

        $response = $this->postJson('v1/login', ['service' => 'facebook', 'token' => 'some-random-token']);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure(['token', 'id']);

        $this->assertDatabaseHas('users', [
            'facebook_id' => 'facebook-id',
            'name' => 'Jhon Doe',
            'email' => 'jhon@doe.com',
            'avatar' => 'https://path.to/avatar',
        ]);

    }

    /** @test */
    public function updates_user_data_in_login()
    {

        $user = factory(User::class)->create([
            'facebook_id' => 'facebook-id',
            'name' => 'Jhon Doe',
            'email' => 'jhon@doe.com',
            'avatar' => 'https://path.to/avatar',
        ]);

        $this->mockSocialite('facebook', 'some-random-token', [
            'id' => 'facebook-id',
            'name' => 'Jhon Doe Alucard',
            'email' => 'newmail@mail.com',
            'avatar' => 'https://new.path.to/avatar',
        ]);

        $response = $this->postJson('v1/login', ['service' => 'facebook', 'token' => 'some-random-token']);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure(['token', 'id']);

        $response->assertJson(['id' => $user->id]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'facebook_id' => 'facebook-id',
            'name' => 'Jhon Doe Alucard',
            'email' => 'newmail@mail.com',
            'avatar' => 'https://new.path.to/avatar',
        ]);

    }

    /** @test */
    public function can_make_request_to_protected_route_after_login()
    {

        $this->mockSocialite('facebook', 'some-random-token');

        $response = $this->postJson('v1/login', ['service' => 'facebook', 'token' => 'some-random-token']);

        $token = $response->json()['token'];

        $this->getJson('v1/feed', ['Authorization' => "Bearer $token"])->assertStatus(Response::HTTP_OK);

    }

    /** @test */
    public function throws_exception_if_no_email_is_retrieved_from_facebook()
    {

        Socialite::shouldReceive('driver')->once()->with('facebook')->andReturn($provider = m::mock(AbstractProvider::class));

        $provider->shouldReceive('userFromToken')->once()->with('some-random-token')->andReturn(m::mock(SocialiteUser::class));

        $response = $this->withExceptionHandling()->postJson('v1/login', ['service' => 'facebook', 'token' => 'some-random-token']);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

    }

    /** @test */
    public function create_pseudonym_for_new_user_if_he_does_not_have_one()
    {

        $this->mockSocialite('facebook', 'some-random-token');

        $userId = $this->postJson('v1/login', ['service' => 'facebook', 'token' => 'some-random-token'])->json()['id'];

        $this->assertDatabaseHas('users', ['id' => $userId]);

        $this->assertDatabaseHas('users', ['real_id' => $userId]);

    }

    /** @test */
    public function do_not_create_pseudonym_for_user_that_already_has_one()
    {

        $user = factory(User::class)->create([
            'facebook_id' => 'facebook-id',
            'name' => 'Jhon Doe',
            'email' => 'jhon@doe.com',
            'avatar' => 'https://path.to/avatar',
        ]);

        factory(User::class)->create(['real_id' => $user->id]);

        $this->mockSocialite('facebook', 'some-random-token', [
            'id' => 'facebook-id',
            'name' => 'Jhon Doe Alucard',
            'email' => 'newmail@mail.com',
            'avatar' => 'https://new.path.to/avatar',
        ]);

        $this->postJson('v1/login', ['service' => 'facebook', 'token' => 'some-random-token']);

        $this->assertEquals(1, User::where('real_id', $user->id)->count());

    }

    /**
     * Mocks socialite for test.
     *
     * @param $service
     * @param $token
     * @param array $data
     * @return m\MockInterface
     */
    protected function mockSocialite($service, $token, $data = [])
    {

        Socialite::shouldReceive('driver')->once()->with($service)->andReturn($provider = m::mock(AbstractProvider::class));

        $user = m::mock(SocialiteUser::class);
        $user->id = $data['id'] ?? 'randomserviceid';
        $user->name = $data['name'] ?? 'Jhon Doe';
        $user->email = $data['email'] ?? 'jhon@doe.com';
        $user->avatar = $data['avatar'] ?? 'https://path.to/avatar';

        $provider->shouldReceive('userFromToken')->once()->with($token)->andReturn($user);

        return $user;

    }

}
