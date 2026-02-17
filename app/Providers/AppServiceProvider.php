<?php

namespace App\Providers;

use App\Domain\Entities\Order;
use App\Observers\OrderObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ページネーションでBootstrapを利用する
        Paginator::useBootstrap();

        // ルートのURLを強制する
        URL::forceRootUrl(Config::get('app.url'));

        // Observer
        Order::observe(OrderObserver::class);

        // ログインスキップ用ルート（ローカル環境の場合のみルートを登録）
        if (App::environment('local')) {
            Route::prefix('skip-login')
                ->middleware([
                    'web',
                ])
                ->group(function () {
                    Route::get('user', static function () {
                        /** @var \App\Domain\Entities\User|null $user */
                        $user = \App\Domain\Entities\User::find(request('id'));
                        if (is_null($user)) {
                            return 'User not found.';
                        }

                        // ログアウト
                        auth()->logout();
                        request()->session()->invalidate();
                        request()->session()->regenerateToken();

                        // ログイン
                        auth()->login($user);

                        // リダイレクト
                        return redirect('/');
                    });
                    Route::get('admin', static function () {
                        /** @var \App\Domain\Entities\Admin|null $admin */
                        $admin = \App\Domain\Entities\Admin::find(request('id'));
                        if (is_null($admin)) {
                            return 'User not found.';
                        }

                        // ログアウト
                        auth()->guard('admin')->logout();
                        request()->session()->invalidate();
                        request()->session()->regenerateToken();

                        // ログイン
                        auth()->guard('admin')->login($admin);

                        // リダイレクト
                        return to_route('admin.home');
                    });
                });
        }
    }
}
