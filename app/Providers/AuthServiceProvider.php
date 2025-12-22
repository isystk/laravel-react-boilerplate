<?php

namespace App\Providers;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
            return AdminRole::HighManager === $admin->role;
        });
    }
}
