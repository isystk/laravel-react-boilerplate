<?php

namespace App\Services\Common;

use App\Domain\Entities\Image;
use App\Domain\Repositories\Image\ImageRepository;
use App\Enums\PhotoType;
use App\Services\BaseService;
use Illuminate\Http\UploadedFile;

class ImageService extends BaseService
{
    public function __construct(
        private readonly ImageRepository $imageRepository
    ) {
    }

    /**
     * 画像を保存し、Imageエンティティを返却する
     */
    public function store(UploadedFile $file, PhotoType $type, string $fileName): Image
    {
        $image = $this->imageRepository->create([
            'file_name' => $fileName,
            'type'      => $type->value,
        ]);

        $file->storeAs($type->type(), $fileName, 's3');

        return $image;
    }
}
