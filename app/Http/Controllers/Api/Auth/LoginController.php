<?php

namespace App\Http\Controllers\Api\Auth;

use App\Domain\Entities\User;
use App\Enums\OperationLogType;
use App\Http\Controllers\Api\BaseApiController;
use App\Services\Common\OperationLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends BaseApiController
{
    public function __construct(private readonly OperationLogService $operationLogService) {}

    /**
     * メール・パスワード認証を行います。
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        /** @var ?User $user */
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        // アカウント停止チェック
        if (!$user->status->isActive()) {
            throw ValidationException::withMessages([
                'email' => [__('auth.suspended')],
            ]);
        }

        Auth::login($user, (bool) $request->boolean('remember'));

        $request->session()->regenerate();

        $this->operationLogService->logUserAction(
            $user->id,
            OperationLogType::UserLogin,
            'ログイン',
            $request->ip()
        );

        return response()->json([
            'name'              => $user->name,
            'email'             => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'avatar_url'        => $user->avatarImage?->getImageUrl() ?? $user->avatar_url,
        ]);
    }
}
