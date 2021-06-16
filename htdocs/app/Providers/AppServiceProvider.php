<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

use App\Services\UserService;
use App\Services\StockService;
use App\Services\MyCartService;
use App\Services\ContactFormService;
use App\Services\OrderService;
use App\Services\PhotoService;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
    $this->app->bind('UserService', UserService::class);
    $this->app->bind('StockService', StockService::class);
    $this->app->bind('MyCartService', MyCartService::class);
    $this->app->bind('ContactFormService', ContactFormService::class);
    $this->app->bind('OrderService', OrderService::class);
    $this->app->bind('PhotoService', PhotoService::class);
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot(UrlGenerator $url)
  {
    $url->forceScheme('https');
  }
}
