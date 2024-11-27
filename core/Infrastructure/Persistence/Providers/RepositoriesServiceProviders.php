<?php

namespace Core\Infrastructure\Persistence\Providers;

use Core\Domain\Repositories\PointRepositoryInterface;
use Core\Infrastructure\Persistence\Repositories\PointRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProviders extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PointRepositoryInterface::class, PointRepository::class);
    }

    public function boot()
    {
        //
    }
}
