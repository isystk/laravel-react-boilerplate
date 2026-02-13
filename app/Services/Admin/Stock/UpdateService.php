<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Dto\Request\Admin\Stock\UpdateDto;
use App\Enums\PhotoType;
use App\Services\BaseService;

class UpdateService extends BaseService
{
    public function __construct(private readonly StockRepository $stockRepository) {}

    /**
     * 商品を更新します。
     */
    public function update(int $stockId, UpdateDto $dto): Stock
    {
        $imageFileName = $dto->imageFileName;

        $data = [
            'name'     => $dto->name,
            'detail'   => $dto->detail,
            'price'    => $dto->price,
            'quantity' => $dto->quantity,
        ];
        if (!empty($imageFileName)) {
            $data['image_file_name'] = $imageFileName;
        }

        $stock = $this->stockRepository->update($data, $stockId);

        // 画像ファイルがある場合はs3にアップロード
        $dto->imageFile?->storeAs(PhotoType::Stock->type(), $dto->imageFileName, 's3');

        return $stock;
    }
}
