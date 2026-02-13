<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Dto\Request\Admin\Stock\CreateDto;
use App\Enums\PhotoType;
use App\Services\BaseService;

class CreateService extends BaseService
{
    public function __construct(private readonly StockRepository $stockRepository) {}

    /**
     * 商品を登録します。
     */
    public function save(CreateDto $dto): Stock
    {
        $imageFileName = $dto->imageFileName;

        $stock = $this->stockRepository->create([
            'name'            => $dto->name,
            'detail'          => $dto->detail,
            'price'           => $dto->price,
            'quantity'        => $dto->quantity,
            'image_file_name' => $imageFileName,
        ]);

        // s3に画像をアップロード
        $dto->imageFile?->storeAs(PhotoType::Stock->type(), $imageFileName, 's3');

        return $stock;
    }
}
