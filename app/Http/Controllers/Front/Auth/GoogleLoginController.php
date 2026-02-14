<?php

namespace App\Http\Controllers\Front\Auth;

use App\Domain\Entities\User;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;
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
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception) {
            return redirect('/login');
        }

        $user = User::updateOrCreate([
            'google_id' => $googleUser->id,
        ], [
            'name'              => $googleUser->name,
            'email'             => $googleUser->email,
            'avatar_url'        => $googleUser->avatar,
            'email_verified_at' => Carbon::now(),
        ]);

        Auth::login($user);

        return redirect()->intended('/');
    }
}
