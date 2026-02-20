<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepositoryInterface;
use App\Dto\Request\Admin\Stock\UpdateDto;
use App\Enums\ImageType;
use App\Services\BaseService;
use App\Services\Common\ImageService;

class UpdateService extends BaseService
{
    public function __construct(
        private readonly StockRepositoryInterface $stockRepository,
        private readonly ImageService $imageService,
    ) {}

    /**
     * 商品を更新します。
     * * 画像の処理ルール:
     * - 既存画像があり、新しいファイル名が空の場合: 画像を紐付け解除（null）
     * - 既存画像があり、新しいファイルがある場合: ImageServiceで画像を更新
     * - 既存画像がなく、新しいファイルがある場合: ImageServiceで新規保存し、IDを紐付け
     */
    public function update(Stock $stock, UpdateDto $dto): Stock
    {
        $data = [
            'name'     => $dto->name,
            'detail'   => $dto->detail,
            'price'    => $dto->price,
            'quantity' => $dto->quantity,
        ];

        if (!empty($stock->image_id) && !$dto->imageFileName) {
            $data['image_id'] = null;
        } elseif (!empty($dto->imageId) && $dto->imageFile) {
            $oldImage = $stock->image;
            $this->imageService->update($oldImage, $dto->imageFile, $dto->imageFileName);
        } elseif ($dto->imageFile) {
            $image = $this->imageService->store(
                $dto->imageFile,
                ImageType::Stock,
                $dto->imageFileName
            );
            $data['image_id'] = $image->id;
        }

        return $this->stockRepository->update($data, $stock->id);
    }
}
