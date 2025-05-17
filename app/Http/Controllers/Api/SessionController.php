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
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
            ],
            'csrf_token' => csrf_token(),
        ]);
    }
}
