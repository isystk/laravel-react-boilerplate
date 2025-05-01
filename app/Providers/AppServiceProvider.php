<?php

namespace App\Providers;

use App\Domain\Entities\Order;
use App\Observers\OrderObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @param UrlGenerator $url
     * @return void
     */
    public function boot(UrlGenerator $url): void
    {
        // httpでアクセスされた際に強制的にhttpsに変換する
        $url->forceScheme('https');

        // ページネーションでBootstrapを利用する
        Paginator::useBootstrap();

        // ルートのURLを強制する
        URL::forceRootUrl(Config::get('app.url'));

        // Observer
        Order::observe(OrderObserver::class);
    }
}
