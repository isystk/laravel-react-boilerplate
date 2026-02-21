<?php

namespace App\Providers;

use App\Domain\Repositories\Admin\AdminRepository;
use App\Domain\Repositories\Admin\AdminRepositoryInterface;
use App\Domain\Repositories\Cart\CartRepository;
use App\Domain\Repositories\Cart\CartRepositoryInterface;
use App\Domain\Repositories\Contact\ContactRepository;
use App\Domain\Repositories\Contact\ContactRepositoryInterface;
use App\Domain\Repositories\ContactReply\ContactReplyRepository;
use App\Domain\Repositories\ContactReply\ContactReplyRepositoryInterface;
use App\Domain\Repositories\Image\ImageRepository;
use App\Domain\Repositories\Image\ImageRepositoryInterface;
use App\Domain\Repositories\ImportHistory\ImportHistoryRepository;
use App\Domain\Repositories\ImportHistory\ImportHistoryRepositoryInterface;
use App\Domain\Repositories\MonthlySale\MonthlySaleRepository;
use App\Domain\Repositories\MonthlySale\MonthlySaleRepositoryInterface;
use App\Domain\Repositories\Order\OrderRepository;
use App\Domain\Repositories\Order\OrderRepositoryInterface;
use App\Domain\Repositories\Order\OrderStockRepository;
use App\Domain\Repositories\Order\OrderStockRepositoryInterface;
use App\Domain\Repositories\Stock\StockRepository;
use App\Domain\Repositories\Stock\StockRepositoryInterface;
use App\Domain\Repositories\User\UserRepository;
use App\Domain\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(ContactReplyRepositoryInterface::class, ContactReplyRepository::class);
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderStockRepositoryInterface::class, OrderStockRepository::class);
        $this->app->bind(StockRepositoryInterface::class, StockRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ImportHistoryRepositoryInterface::class, ImportHistoryRepository::class);
        $this->app->bind(MonthlySaleRepositoryInterface::class, MonthlySaleRepository::class);
    }
}
