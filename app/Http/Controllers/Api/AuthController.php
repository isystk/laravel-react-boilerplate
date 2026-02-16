<?php

namespace App\Http\Controllers\Api;

use App\Dto\Response\Api\Auth\AuthenticateDto;
use App\Helpers\AuthHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        if (is_null($user)) {
            throw new UnauthorizedHttpException('jwt-auth');
        }

        $dto = new AuthenticateDto(
            $user->name,
            $user->avatarImage?->getImageUrl() ?? $user->avatar_url,
        );

        return response()->json($dto);
    }
}
