<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AuthHelper;
use Illuminate\Http\JsonResponse;

class SessionController extends BaseApiController
{
    /**
     * ログイン情報を返却します。
     */
    public function index(): JsonResponse
    {
        $user = AuthHelper::frontLoginedUser();

        return response()->json([
            'id' => $user->id ?? null,
            'name' => $user->name ?? null,
            'email' => $user->email ?? null,
            'email_verified_at' => $user->email_verified_at ?? null,
        ]);
    }
}
