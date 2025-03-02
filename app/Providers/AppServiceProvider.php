<?php

namespace App\Providers;

use App\Http\Repositories\Product\ProductRepositories;
use App\Http\Repositories\User\UserRepository;
use App\Interface\Product\ProductRepositoryInterface;
use App\Interface\User\UserRepositoryInterface;
use App\Services\ProductService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //user
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserService::class, function ($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });
        //product
        $this->app->bind(ProductRepositoryInterface::class, ProductRepositories::class);
        $this->app->bind(ProductService::class, function ($app){
            return new ProductService($app->make(ProductRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
