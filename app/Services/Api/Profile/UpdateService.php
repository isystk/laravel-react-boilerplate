<?php

namespace App\Services\Api\Profile;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Dto\Response\Api\Profile\UpdateDto;
use App\Enums\ImageType;
use App\Enums\OperationLogType;
use App\Services\Common\ImageService;
use App\Services\Common\OperationLogService;
use App\Utils\UploadImage;

class UpdateService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ImageService $imageService,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * プロフィール情報を更新します。
     */
    public function update(User $user, string $name, ?string $avatar): UpdateDto
    {
        $model = [
            'name' => $name,
        ];

        // アバター処理
        $image = null;
        if (!empty($avatar)) {

            // Base64エンコードされた画像データをUploadedFileに変換します。
            $imageFile = UploadImage::convertBase64($avatar);

            $image                    = $this->imageService->store($imageFile, ImageType::User, $imageFile->getClientOriginalName());
            $model['avatar_image_id'] = $image->id;
        }

        $user = $this->userRepository->update($model, $user->id);

        $avatarUrl = $user->avatarImage?->getImageUrl() ?? $user->avatar_url;

        $this->operationLogService->logUserAction(
            $user->id,
            OperationLogType::UserProfileUpdate,
            'プロフィールを更新',
            request()->ip()
        );

        return new UpdateDto($user->name, $avatarUrl);
    }
}
