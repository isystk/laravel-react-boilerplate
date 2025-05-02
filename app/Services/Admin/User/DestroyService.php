<?php

namespace App\Services\Admin\User;

use App\Domain\Repositories\User\UserRepository;
use App\Services\BaseService;

class DestroyService extends BaseService
{
    private UserRepository $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * ユーザーを削除します。
     * @param int $id
     */
    public function delete(int $id): void
    {
        $this->userRepository->delete($id);
    }
}
