<?php

namespace App\Providers;

use App\Domain\Entities\Admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('high-manager', static function (Admin $admin) {
            return $admin->role === 'high-manager';
        });

    }
}
