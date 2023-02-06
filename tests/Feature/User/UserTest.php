<?php

namespace Tests\Feature\User;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_be_create_an_user_successfully()
    {
        //arrange
        $userPayload = UserFactory::new()->definition();
        $userPayload['password_confirmation'] = $userPayload['password'];

        //act
        $response = $this->post('/api/v1/users', $userPayload);

        //assert
        $response->assertSuccessful();
        $this->assertDatabaseHas('users', [
            'name' => $userPayload['name'],
            'email' => $userPayload['email']
        ]);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'remember_token'
            ]
        ]);
    }

    public function test_it_should_not_be_create_an_user_why_this_email_already_been_used()
    {
        //arrange
        $user = UserFactory::new()->create();
        $newUser = UserFactory::new()->definition();
        $newUser['email'] = $user['email'];
        $newUser['password_confirmation'] = $newUser['password'];

        //act
        $response = $this->post('/api/v1/users', $newUser);

        //assert
        $this->assertDatabaseMissing('users', [
            'name' => $newUser['name'],
            'email' => $newUser['email']
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_it_should_not_be_create_an_user_why_password_confirmation_is_different_from_the_password()
    {
        //arrange
        $user = UserFactory::new()->create();
        $newUser = UserFactory::new()->definition();
        $newUser['email'] = $user['email'];
        $newUser['password_confirmation'] = \Str::random(10);

        //act
        $response = $this->post('/api/v1/users', $newUser);

        //assert
        $this->assertDatabaseMissing('users', [
            'name' => $newUser['name'],
            'email' => $newUser['email']
        ]);

        $response->assertSessionHasErrors(['password_confirmation']);
    }
}
