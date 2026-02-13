<?php

namespace App\Services\Admin\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use App\Dto\Request\Admin\User\UpdateDto;
use App\Services\BaseService;

class UpdateService extends BaseService
{
    public function __construct(private readonly UserRepository $userRepository) {}

    /**
     * ユーザーを更新します。
     */
    public function update(int $userId, UpdateDto $dto): User
    {
        return $this->userRepository->update([
            'name'  => $dto->name,
            'email' => $dto->email,
        ], $userId);
    }
}
