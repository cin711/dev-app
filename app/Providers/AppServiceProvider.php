<?php

namespace App\Providers;

use App\Exceptions\Handler;
use App\Repositories\DepartmentRepository;
use App\Repositories\UserRepository;
use App\Services\DepartmentService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DepartmentRepository::class);
        $this->app->singleton(DepartmentService::class);
        $this->app->singleton(UserRepository::class);
        $this->app->singleton(UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
