<?php

namespace App\Repositories\User\Repositories;

use App\Models\User;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Traits\HasToken;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    use HasToken, HasApiTokens;

    public function create(array $payload): User
    {
       return User::query()->create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => $payload['password'],
        ]);
    }

    public function getTokenByEmail(string $email, string $password): string|null
    {
        $user = User::query()->where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        return $user->createToken('access')->plainTextToken;
    }
}
