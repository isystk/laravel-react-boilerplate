<?php

namespace App\Providers;

use App\Domain\Repositories\Admin\AdminEloquentRepository;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Domain\Repositories\Cart\CartEloquentRepository;
use App\Domain\Repositories\Cart\CartRepository;
use App\Domain\Repositories\ContactForm\ContactFormEloquentRepository;
use App\Domain\Repositories\ContactForm\ContactFormImageEloquentRepository;
use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Domain\Repositories\ImportHistory\ImportHistoryEloquentRepository;
use App\Domain\Repositories\ImportHistory\ImportHistoryRepository;
use App\Domain\Repositories\Order\OrderEloquentRepository;
use App\Domain\Repositories\Order\OrderRepository;
use App\Domain\Repositories\Order\OrderStockEloquentRepository;
use App\Domain\Repositories\Order\OrderStockRepository;
use App\Domain\Repositories\Stock\StockEloquentRepository;
use App\Domain\Repositories\Stock\StockRepository;
use App\Domain\Repositories\User\UserEloquentRepository;
use App\Domain\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminRepository::class, AdminEloquentRepository::class);
        $this->app->bind(CartRepository::class, CartEloquentRepository::class);
        $this->app->bind(ContactFormRepository::class, ContactFormEloquentRepository::class);
        $this->app->bind(ContactFormImageRepository::class, ContactFormImageEloquentRepository::class);
        $this->app->bind(OrderRepository::class, OrderEloquentRepository::class);
        $this->app->bind(OrderStockRepository::class, OrderStockEloquentRepository::class);
        $this->app->bind(StockRepository::class, StockEloquentRepository::class);
        $this->app->bind(UserRepository::class, UserEloquentRepository::class);
        $this->app->bind(ImportHistoryRepository::class, ImportHistoryEloquentRepository::class);
    }

}
