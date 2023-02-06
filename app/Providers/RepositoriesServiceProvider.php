<?php

namespace App\Providers;

use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\User\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function boot()
    {
    }
}
