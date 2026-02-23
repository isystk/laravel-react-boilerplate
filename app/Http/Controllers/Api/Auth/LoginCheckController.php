<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\AuthHelper;
use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LoginCheckController extends BaseApiController
{
    /**
     * ログイン情報を返却します。
     */
    public function index(): JsonResponse
    {
        $user = AuthHelper::frontLoginedUser();

        // アカウント停止チェック
        if (!$user->status->isActive()) {
            throw ValidationException::withMessages([
                'email' => [__('auth.suspended')],
            ]);
        }

        return response()->json([
            'name'              => $user->name,
            'email'             => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'avatar_url'        => $user->avatarImage?->getImageUrl() ?? $user->avatar_url,
        ]);
    }
}
