<?php

namespace Tests\Feature\User;

use App\Repositories\User\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_a_user_using_repository_interface()
    {
        //arrange
        $userData = [
            "name" => "exemplo",
            "email" => "email@example.com",
            "password" => "123456",
            "password_confirmation" => "123456"
        ];

        $repository = New UserRepository();

        //act
        $user = $repository->create($userData);

        //assert
        $this->assertDatabaseHas('users', [
           'name' => $userData['name'],
           'email' => $userData['email'],
        ]);
    }
}
