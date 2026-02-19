<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\BaseController;
use App\Services\Front\Auth\GoogleLogin\HandleGoogleCallbackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends BaseController
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $service = app(HandleGoogleCallbackService::class);

        DB::beginTransaction();

        try {
            $googleUser = Socialite::driver('google')->user();

            $user = $service->findOrCreate($googleUser);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }

        if (!$user->status->isActive()) {
            return to_route('login')->withErrors([
                'email' => __('auth.suspended'),
            ]);
        }

        Auth::login($user);

        return redirect()->intended('/home');
    }
}
