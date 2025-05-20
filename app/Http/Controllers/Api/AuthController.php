<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AuthHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseApiController
{
    /**
     * ログイン処理
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['token' => $token]);
    }

    /**
     * ログアウト処理
     */
    public function logout(): JsonResponse
    {
        AuthHelper::frontLogout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * ログインチェック
     */
    public function authenticate(): JsonResponse
    {
        $user = AuthHelper::frontLoginedUser();

        return response()->json($user);
    }
}
