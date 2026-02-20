<?php

namespace App\Services\Common;

use App\Domain\Entities\Image;
use App\Domain\Repositories\Image\ImageRepositoryInterface;
use App\Enums\ImageType;
use App\Services\BaseService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService extends BaseService
{
    public function __construct(
        private readonly ImageRepositoryInterface $imageRepository
    ) {}

    /**
     * 画像を保存し、Imageエンティティを返却する
     */
    public function store(UploadedFile $file, ImageType $type, string $fileName): Image
    {
        $image = $this->imageRepository->create([
            'file_name' => $fileName,
            'type'      => $type,
        ]);

        $directory = $type->type() . '/' . $image->getHashedDirectory();
        $file->storeAs($directory, $fileName, 's3');

        return $image;
    }

    /**
     * 画像を更新し、更新後のImageエンティティを返却する
     */
    public function update(Image $image, UploadedFile $file, string $fileName): Image
    {
        $oldPath = $image->getS3Path();
        if (Storage::disk('s3')->exists($oldPath)) {
            Storage::disk('s3')->delete($oldPath);
        }

        $this->imageRepository->update([
            'file_name' => $fileName,
            'type'      => $image->type,
        ], $image->id);

        $directory = $image->type->type() . '/' . $image->getHashedDirectory();
        $file->storeAs($directory, $fileName, 's3');

        $image->refresh();

        return $image;
    }

    /**
     * 画像を削除する
     */
    public function delete(Image $image): void
    {
        $path = $image->getS3Path();
        if (Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
        }

        $this->imageRepository->delete($image->id);
    }
}
