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
        $user = $this->userRepository->findByGoogleId($googleUser->id);

        if (is_null($user)) {
            $user = $this->userRepository->create([
                'name'              => $googleUser->name,
                'email'             => $googleUser->email,
                'avatar_url'        => $googleUser->avatar,
                'email_verified_at' => Carbon::now(),
                'google_id'         => $googleUser->id,
                'status'            => UserStatus::Active,
            ]);
        }

        return $user;
    }
}
