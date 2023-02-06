<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;

trait HasToken
{
    public function generateToken(): string
    {
        return hash('sha256', Str::random(40));
    }
}
