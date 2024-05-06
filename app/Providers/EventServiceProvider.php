<?php

namespace App\Providers;

use App\Domain\Entities\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Order::observe(OrderObserver::class);
    }

}
