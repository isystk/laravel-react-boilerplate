<?php

namespace App\Services\Admin\Photo;

use App\Domain\Repositories\Image\ImageRepositoryInterface;
use App\Enums\OperationLogType;
use App\Services\BaseService;
use App\Services\Common\ImageService;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Auth;

class DestroyService extends BaseService
{
    public function __construct(
        private readonly ImageRepositoryInterface $imageRepository,
        private readonly ImageService $imageService,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * 画像を削除します。
     */
    public function delete(int $imageId): void
    {
        $image = $this->imageRepository->findById($imageId);
        $this->imageService->delete($image);

        $this->operationLogService->logAdminAction(
            Auth::guard('admin')->id(),
            OperationLogType::AdminImageDelete,
            "画像を削除 (画像ID: {$imageId})",
            request()->ip()
        );
    }
}
