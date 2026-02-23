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
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends BaseApiController
{
    public function __construct(private readonly OperationLogService $operationLogService) {}

    /**
     * 新規ユーザーを作成してログインします。
     */
    public function __invoke(Request $request): JsonResponse
    {
        $maxlength = config('const.maxlength.users');

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:' . $maxlength['name'],
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:' . $maxlength['email'],
                Rule::unique(User::class),
            ],
            'password' => [
                'required',
                'string',
                Password::default(),
                'confirmed',
            ],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $this->operationLogService->logUserAction(
            $user->id,
            OperationLogType::UserAccountCreate,
            'アカウントを作成',
            $request->ip()
        );

        Auth::login($user);

        $request->session()->regenerate();

        $user->sendEmailVerificationNotification();

        return response()->json([
            'name'              => $user->name,
            'email'             => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'avatar_url'        => null,
        ], 201);
    }
}
