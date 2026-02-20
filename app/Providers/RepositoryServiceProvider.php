<?php

namespace App\Providers;

use App\Domain\Repositories\Admin\AdminEloquentRepository;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Domain\Repositories\Cart\CartEloquentRepository;
use App\Domain\Repositories\Cart\CartRepository;
use App\Domain\Repositories\Contact\ContactEloquentRepository;
use App\Domain\Repositories\Contact\ContactRepository;
use App\Domain\Repositories\ContactReply\ContactReplyEloquentRepository;
use App\Domain\Repositories\ContactReply\ContactReplyRepository;
use App\Domain\Repositories\Image\ImageEloquentRepository;
use App\Domain\Repositories\Image\ImageRepository;
use App\Domain\Repositories\ImportHistory\ImportHistoryEloquentRepository;
use App\Domain\Repositories\ImportHistory\ImportHistoryRepository;
use App\Domain\Repositories\MonthlySale\MonthlySaleEloquentRepository;
use App\Domain\Repositories\MonthlySale\MonthlySaleRepository;
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
        $this->app->bind(ContactRepository::class, ContactEloquentRepository::class);
        $this->app->bind(ContactReplyRepository::class, ContactReplyEloquentRepository::class);
        $this->app->bind(ImageRepository::class, ImageEloquentRepository::class);
        $this->app->bind(OrderRepository::class, OrderEloquentRepository::class);
        $this->app->bind(OrderStockRepository::class, OrderStockEloquentRepository::class);
        $this->app->bind(StockRepository::class, StockEloquentRepository::class);
        $this->app->bind(UserRepository::class, UserEloquentRepository::class);
        $this->app->bind(ImportHistoryRepository::class, ImportHistoryEloquentRepository::class);
        $this->app->bind(MonthlySaleRepository::class, MonthlySaleEloquentRepository::class);
    }
}
