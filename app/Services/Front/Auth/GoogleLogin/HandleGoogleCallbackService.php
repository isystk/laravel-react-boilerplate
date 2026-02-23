<?php

namespace App\Services\Front\Auth\GoogleLogin;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Enums\OperationLogType;
use App\Enums\UserStatus;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Carbon;

class HandleGoogleCallbackService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * Googleユーザー情報からユーザーを検索し、存在しない場合は新規作成します。
     */
    public function findOrCreate(\Laravel\Socialite\Contracts\User $googleUser): User
    {
        $user = $this->userRepository->findByGoogleIdWithTrashed($googleUser->getId());

        if (is_null($user)) {
            // Google IDで見つからない場合は、メールアドレスで検索
            $user = $this->userRepository->findByEmailWithTrashed($googleUser->getEmail());
        }

        if (is_null($user)) {
            $user = $this->userRepository->create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'avatar_url'        => $googleUser->getAvatar(),
                'email_verified_at' => Carbon::now(),
                'google_id'         => $googleUser->getId(),
                'status'            => UserStatus::Active,
            ]);

            $this->operationLogService->logUserAction(
                $user->id,
                OperationLogType::UserAccountCreate,
                'アカウントを作成 (Google)',
                request()->ip()
            );

        } else {
            // 既存ユーザーが削除されている場合は復元
            if (!is_null($user->deleted_at)) {
                $this->userRepository->restore($user->id);
                $user->refresh();
            }
            // 既存ユーザーがGoogle IDを持っていない場合は更新
            if (is_null($user->google_id)) {
                $user = $this->userRepository->update([
                    'google_id' => $googleUser->getId(),
                ], $user->id);
            }
        }

        return $user;
    }
}
