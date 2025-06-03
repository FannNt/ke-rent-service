<?php

namespace App\Providers;

use App\Http\Controllers\AdminController;
use App\Http\Repositories\Terms\TermsRepository;
use App\Http\Repositories\Transaction\TransactionRepository;
use App\Http\Repositories\Chat\ChatRepository;
use App\Http\Repositories\Message\MessageRepository;

use App\Http\Repositories\Product\ProductRepositories;
use App\Http\Repositories\User\UserRepository;
use App\Interface\Chat\ChatRepositoryInterface;
use App\Interface\Message\MessageRepositoryInterface;
use App\Interface\Product\ProductRepositoryInterface;
use App\Interface\User\UserRepositoryInterface;
use App\Services\AdminService;
use App\Services\ChatService;
use App\Services\CloudinaryService;
use App\Interface\Transaction\TransactionRepositoryInterface;
use App\Services\MidtransService;
use App\Services\ProductService;
use App\Services\TermsService;
use App\Services\TextractService;
use App\Services\TransactionService;
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

        //admin
        $this->app->bind(AdminService::class);
        $this->app->bind(AdminController::class);

        //terms
        $this->app->bind(TermsRepository::class);
        $this->app->bind(TermsService::class);

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

        // transaction
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);

        $this->app->bind(TransactionService::class);

        $this->app->bind(TextractService::class);

        //midtrans
        $this->app->bind(MidtransService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
