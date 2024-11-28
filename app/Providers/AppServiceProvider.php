<?php

namespace App\Providers;

use Core\Infrastructure\Persistence\Models\Point;
use Core\Infrastructure\Policies\PointPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Point::class, PointPolicy::class);
    }
}
