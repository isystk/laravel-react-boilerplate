<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Dto\Request\Admin\Stock\UpdateDto;
use App\Enums\PhotoType;
use App\Services\BaseService;
use App\Services\Common\ImageService;

class UpdateService extends BaseService
{
    public function __construct(
        private readonly StockRepository $stockRepository,
        private readonly ImageService $imageService,
    ) {}

    /**
     * 商品を更新します。
     */
    public function update(int $stockId, UpdateDto $dto): Stock
    {
        $data = [
            'name'     => $dto->name,
            'detail'   => $dto->detail,
            'price'    => $dto->price,
            'quantity' => $dto->quantity,
        ];

        if ($dto->imageFile) {
            $image = $this->imageService->store(
                $dto->imageFile,
                PhotoType::Stock,
                $dto->imageFileName
            );
            $data['image_id'] = $image->id;
        }

        return $this->stockRepository->update($data, $stockId);
    }
}
