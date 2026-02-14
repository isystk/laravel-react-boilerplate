<?php

namespace App\Services\Admin\Photo;

use App\Domain\Repositories\Image\ImageRepository;
use App\Services\BaseService;
use App\Services\Common\ImageService;

class DestroyService extends BaseService
{
    public function __construct(
        private readonly ImageRepository $imageRepository,
        private readonly ImageService $imageService,
    ) {}

    /**
     * 画像を削除します。
     */
    public function delete(int $imageId): void
    {
        $image = $this->imageRepository->findById($imageId);
        $this->imageService->delete($image);
    }
}
