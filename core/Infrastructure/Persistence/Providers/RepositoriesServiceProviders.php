<?php

namespace Core\Infrastructure\Persistence\Providers;

use Core\Domain\Repositories\PointRepositoryInterface;
use Core\Domain\Repositories\UserRepositoryInterface;
use Core\Infrastructure\Persistence\Repositories\PointRepository;
use Core\Infrastructure\Persistence\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProviders extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PointRepositoryInterface::class, PointRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function boot()
    {
        //
    }
}
