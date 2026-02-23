<?php

namespace App\Services\Admin\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Dto\Request\Admin\User\UpdateDto;
use App\Enums\ImageType;
use App\Enums\OperationLogType;
use App\Services\BaseService;
use App\Services\Common\ImageService;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Auth;

class UpdateService extends BaseService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ImageService $imageService,
        private readonly OperationLogService $operationLogService,
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

        $user = $this->userRepository->update($model, $userId);

        $this->operationLogService->logAdminAction(
            Auth::guard('admin')->id(),
            OperationLogType::AdminUserUpdate,
            "ユーザー情報を更新 (ユーザーID: {$userId})",
            request()->ip()
        );

        return $user;
    }
}
