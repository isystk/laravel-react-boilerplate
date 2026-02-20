<?php

namespace App\Services\Admin\User;

use App\Domain\Repositories\User\UserRepository;
use App\Enums\UserStatus;
use App\Services\BaseService;

class SuspendService extends BaseService
{
    public function __construct(private readonly UserRepository $userRepository) {}

    /**
     * ユーザーをアカウント停止にします。
     */
    public function suspend(int $id): void
    {
        $this->userRepository->update([
            'status' => UserStatus::Suspended,
        ], $id);
    }

    /**
     * ユーザーを有効にします。
     */
    public function activate(int $id): void
    {
        $this->userRepository->update([
            'status' => UserStatus::Active,
        ], $id);
    }
}
