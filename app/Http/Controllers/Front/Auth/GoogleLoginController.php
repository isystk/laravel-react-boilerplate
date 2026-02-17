<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\BaseController;
use App\Services\Front\Auth\GoogleLogin\HandleGoogleCallbackService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends BaseController
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $service = app(HandleGoogleCallbackService::class);

        try {
            $googleUser = Socialite::driver('google')->user();

            $user = $service->findOrCreate($googleUser);
            Auth::login($user);

        } catch (\Throwable) {
            return redirect('/login');
        }

        return redirect()->intended('/home');
    }
}
