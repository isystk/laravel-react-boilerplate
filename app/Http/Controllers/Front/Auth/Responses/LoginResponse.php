<?php

namespace App\Http\Controllers\Front\Auth\Responses;

use App\Enums\OperationLogType;
use App\Helpers\AuthHelper;
use App\Services\Common\OperationLogService;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    public function __construct(private readonly OperationLogService $operationLogService) {}

    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     */
    public function toResponse($request): Response
    {
        $user = AuthHelper::frontLoginedUser();

        if ($user !== null) {
            $this->operationLogService->logUserAction(
                $user->id,
                OperationLogType::UserLogin,
                'ログイン',
                $request->ip()
            );
        }

        return redirect()->intended(config('fortify.home'));
    }
}
