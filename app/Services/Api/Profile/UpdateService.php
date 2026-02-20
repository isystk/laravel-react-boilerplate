<?php

namespace App\Services\Api\Profile;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use App\Dto\Response\Api\Profile\UpdateDto;
use App\Enums\ImageType;
use App\Services\Common\ImageService;
use App\Utils\UploadImage;

class UpdateService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ImageService $imageService
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
        if (!empty($avatar)) {

            // Base64エンコードされた画像データをUploadedFileに変換します。
            $imageFile = UploadImage::convertBase64($avatar);

            $image                    = $this->imageService->store($imageFile, ImageType::User, $imageFile->getClientOriginalName());
            $model['avatar_image_id'] = $image->id;
        }

        $this->userRepository->update($model, $user->id);

        $avatarUrl = $image?->getImageUrl() ?? $user->avatar_url;

        return new UpdateDto($user->name, $avatarUrl);
    }
}
