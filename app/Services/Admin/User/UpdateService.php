<?php

namespace App\Services\Admin\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Services\BaseService;

class UpdateService extends BaseService
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * ユーザーを更新します。
     */
    public function update(int $userId, UpdateRequest $request): User
    {
        return $this->userRepository->update(
            $userId,
            [
                'name' => $request->name,
                'email' => $request->email,
            ]
        );
    }
}
