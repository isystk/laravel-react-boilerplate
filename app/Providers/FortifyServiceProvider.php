<?php

namespace App\Providers;

use App\Domain\Entities\User;
use App\Http\Controllers\Front\Auth\CreateNewUser;
use App\Http\Controllers\Front\Auth\ResetUserPassword;
use App\Http\Controllers\Front\Auth\Responses\LoginResponse;
use App\Http\Controllers\Front\Auth\UpdateUserPassword;
use App\Http\Controllers\Front\Auth\UpdateUserProfileInformation;
use App\Listeners\LogUserLogoutListener;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView(static fn () => view('front.react'));
        Fortify::registerView(static fn () => view('front.react'));
        Fortify::requestPasswordResetLinkView(static fn () => view('front.react'));
        Fortify::resetPasswordView(static fn () => view('front.react'));
        Fortify::verifyEmailView(static fn () => view('front.react'));

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::authenticateUsing(static function ($request) {
            /** @var ?User $user */
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return null;
            }

            // アカウント停止チェック
            if (!$user->status->isActive()) {
                throw ValidationException::withMessages([
                    'email' => [__('auth.suspended')],
                ]);
            }

            return $user;
        });

        RateLimiter::for('login', static function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email . $request->ip());
        });

        RateLimiter::for('two-factor', static fn (Request $request) => Limit::perMinute(5)->by($request->session()->get('login.id')));

        // Event Listener
        Event::listen(Logout::class, LogUserLogoutListener::class);
    }
}
