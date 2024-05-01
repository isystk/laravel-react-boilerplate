<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            // ローカル環境の場合にだけ、Seleniumを使用する為に必要な設定を追加する
            $this->app->register(DuskServiceProvider::class);
        }
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
    }
}
