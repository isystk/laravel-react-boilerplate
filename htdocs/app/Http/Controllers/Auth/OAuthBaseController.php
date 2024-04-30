<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Entities\User;
use App\Http\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthBaseController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * 各SNSのOAuth認証画面にリダイレクトして認証
     * @param string $provider サービス名
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function socialOAuth(string $provider): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * 各サイトからのコールバック
     * @param string $provider サービス名
     * @return RedirectResponse
     */
    public function handleProviderCallback(string $provider): RedirectResponse
    {
        // @phpstan-ignore-next-line
        $socialUser = Socialite::driver($provider)->stateless()->user();
        $user = User::firstOrNew(['email' => $socialUser->getEmail()]); // TODO Eloquantで検索

        // すでに会員になっている場合の処理を書く
        // そのままログインさせてもいいかもしれない
        if ($user->exists) {
            abort(403);
        }

        $user->name = $socialUser->getName();
        $user->provider_id = $socialUser->getId();
        $user->provider_name = $provider;
        $user->save();

        Auth::login($user);

        return redirect(route('home'));
    }
}
