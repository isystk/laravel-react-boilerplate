<?php

namespace App\Services\Admin\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use App\Dto\Request\Admin\User\UpdateDto;
use App\Enums\ImageType;
use App\Services\BaseService;
use App\Services\Common\ImageService;

class UpdateService extends BaseService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ImageService $imageService,
    ) {}

    /**
     * ユーザーを更新します。
     */
    public function update(int $userId, UpdateDto $dto): User
    {
        $model = [
            'name'  => $dto->name,
            'email' => $dto->email,
        ];

        // アバター処理（ファイルがアップロードされた場合）
        if (!empty($dto->avatar)) {
            $user = $this->userRepository->findById($userId);

            if ($user === null) {
                throw new \RuntimeException('User not found');
            }

            if ($user->avatarImage) {
                // 既存の Image を更新
                $image = $this->imageService->update($user->avatarImage, $dto->avatar, $dto->avatar->getClientOriginalName());
            } else {
                // 新規に Image を作成
                $image = $this->imageService->store($dto->avatar, ImageType::User, $dto->avatar->getClientOriginalName());
            }

            $model['avatar_url']      = null;
            $model['avatar_image_id'] = $image->id;
        }

        return $this->userRepository->update($model, $userId);
    }
}
