<?php

namespace App\Services\Front\Auth\GoogleLogin;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use App\Enums\UserStatus;
use Carbon\Carbon;

class HandleGoogleCallbackService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    /**
     * Googleユーザー情報からユーザーを検索し、存在しない場合は新規作成します。
     */
    public function findOrCreate(\Laravel\Socialite\Contracts\User $googleUser): User
    {
        $user = $this->userRepository->findByGoogleIdWithTrashed($googleUser->getId());

        if (is_null($user)) {
            $user = $this->userRepository->create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'avatar_url'        => $googleUser->getAvatar(),
                'email_verified_at' => Carbon::now(),
                'google_id'         => $googleUser->getId(),
                'status'            => UserStatus::Active,
            ]);
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
