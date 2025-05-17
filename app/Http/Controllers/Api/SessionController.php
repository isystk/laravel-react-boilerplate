<?php

namespace App\Http\Controllers\Api;

use App\Domain\Entities\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends BaseApiController
{
    /**
     * ログイン情報を返却します。
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        return response()->json([
            'id' => $user->id ?? null,
            'name' => $user->name ?? null,
            'email' => $user->email ?? null,
            'email_verified_at' => $user->email_verified_at ?? null,
        ]);
    }
}
