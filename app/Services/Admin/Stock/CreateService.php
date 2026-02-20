<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepositoryInterface;
use App\Dto\Request\Admin\Stock\CreateDto;
use App\Enums\ImageType;
use App\Services\BaseService;
use App\Services\Common\ImageService;

class CreateService extends BaseService
{
    public function __construct(
        private readonly StockRepositoryInterface $stockRepository,
        private readonly ImageService $imageService,
    ) {}

    /**
     * 商品を登録します。
     */
    public function save(CreateDto $dto): Stock
    {
        // 画像をアップロード
        $image = $this->imageService->store($dto->imageFile, ImageType::Stock, $dto->imageFileName);

        return $this->stockRepository->create([
            'name'     => $dto->name,
            'detail'   => $dto->detail,
            'price'    => $dto->price,
            'quantity' => $dto->quantity,
            'image_id' => $image->id,
        ]);
    }
}
