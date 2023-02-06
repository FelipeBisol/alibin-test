<?php

namespace App\Repositories\User\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $payload): User;

    public function getTokenByEmail(string $email, string $password): string|null;
}
