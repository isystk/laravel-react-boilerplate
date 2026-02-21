<?php

namespace App\Http\Controllers\Front\Fortify;

use App\Enums\OperationLogType;
use App\Http\Controllers\BaseController;
use App\Services\Common\OperationLogService;
use App\Services\Front\Auth\GoogleLogin\HandleGoogleCallbackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class GoogleLoginController extends BaseController
{
    public function __construct(private readonly OperationLogService $operationLogService) {}

    public function redirectToGoogle(): SymfonyRedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $service = app(HandleGoogleCallbackService::class);

        DB::beginTransaction();

        try {
            $googleUser = Socialite::driver('google')->user();

            $user = $service->findOrCreate($googleUser);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }

        if (!$user->status->isActive()) {
            return to_route('login')->withErrors([
                'email' => __('auth.suspended'),
            ]);
        }

        Auth::login($user);

        $this->operationLogService->logUserAction(
            $user->id,
            OperationLogType::UserLogin,
            'ログイン (Google)',
            request()->ip()
        );

        return redirect()->intended('/home');
    }
}
