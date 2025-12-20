<?php

namespace App\Helpers;

use App\Domain\Entities\User;
use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    /**
     * フロントエンドにログイン中のユーザーを返却します。
     */
    public static function frontLoginedUser(): ?User
    {
        return Auth::user() ?? Auth::guard('api')->user();
    }

    /**
     * フロントエンドのログアウトをします。
     */
    public static function frontLogout(): void
    {
        Auth::logout();
        Auth::guard('api')->logout();
    }
}
