<?php

namespace App\Services\Admin\User;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;

class DestroyService extends BaseService
{
    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    /**
     * ユーザーを削除します。
     */
    public function delete(int $id): void
    {
        $this->userRepository->delete($id);
    }
}
