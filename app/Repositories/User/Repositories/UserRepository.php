<?php

namespace App\Repositories\User\Repositories;

use App\Models\User;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Traits\HasToken;
use Laravel\Sanctum\HasApiTokens;

class UserRepository implements UserRepositoryInterface
{
    use HasToken, HasApiTokens;

    public function create(array $payload): User
    {
        return User::query()->create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => \Hash::make($payload['password']),
            'remember_token' => $this->generateToken()
        ]);
    }
}
