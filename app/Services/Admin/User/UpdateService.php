<?php

namespace App\Services\Admin\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Services\BaseService;

class UpdateService extends BaseService
{
    public function __construct(private readonly UserRepository $userRepository) {}

    /**
     * ユーザーを更新します。
     */
    public function update(int $userId, UpdateRequest $request): User
    {
        return $this->userRepository->update([
            'name'  => $request->name,
            'email' => $request->email,
        ], $userId);
    }
}
