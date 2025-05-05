<?php

namespace App\Providers;

use App\Http\Repositories\Chat\ChatRepository;
use App\Http\Repositories\Message\MessageRepository;
use App\Http\Repositories\Product\ProductRepositories;
use App\Http\Repositories\User\UserRepository;
use App\Interface\Chat\ChatRepositoryInterface;
use App\Interface\Message\MessageRepositoryInterface;
use App\Interface\Product\ProductRepositoryInterface;
use App\Interface\User\UserRepositoryInterface;
use App\Services\ChatService;
use App\Services\CloudinaryService;
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
        $this->app->bind(UserService::class);
        //product
        $this->app->bind(ProductRepositoryInterface::class, ProductRepositories::class);
        $this->app->bind(ProductService::class);

        //message
        $this->app->bind(MessageRepositoryInterface::class,MessageRepository::class);
        //chat
        $this->app->bind(ChatRepositoryInterface::class,ChatRepository::class);
        $this->app->bind(ChatService::class);

        //cloudinary
        $this->app->bind( CloudinaryService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
