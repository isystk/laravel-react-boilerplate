<?php

namespace App\Providers;

use App\Domain\Entities\Cart;
use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\Admin\AdminEloquentEloquentRepository;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Domain\Repositories\Cart\CartEloquentEloquentRepository;
use App\Domain\Repositories\Cart\CartRepository;
use App\Domain\Repositories\ContactForm\ContactFormEloquentEloquentRepository;
use App\Domain\Repositories\ContactForm\ContactFormImageEloquentEloquentRepository;
use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Domain\Repositories\Order\OrderEloquentEloquentRepository;
use App\Domain\Repositories\Order\OrderRepository;
use App\Domain\Repositories\Stock\StockEloquentEloquentRepository;
use App\Domain\Repositories\Stock\StockRepository;
use App\Domain\Repositories\User\UserEloquentEloquentRepository;
use App\Domain\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminRepository::class, AdminEloquentEloquentRepository::class);
        $this->app->bind(CartRepository::class, CartEloquentEloquentRepository::class);
        $this->app->bind(ContactFormRepository::class, ContactFormEloquentEloquentRepository::class);
        $this->app->bind(ContactFormImageRepository::class, ContactFormImageEloquentEloquentRepository::class);
        $this->app->bind(OrderRepository::class, OrderEloquentEloquentRepository::class);
        $this->app->bind(StockRepository::class, StockEloquentEloquentRepository::class);
        $this->app->bind(UserRepository::class, UserEloquentEloquentRepository::class);
    }

}
