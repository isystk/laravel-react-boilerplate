<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepositoryInterface;
use App\Dto\Request\Admin\Stock\CreateDto;
use App\Enums\ImageType;
use App\Enums\OperationLogType;
use App\Services\BaseService;
use App\Services\Common\ImageService;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Auth;

class CreateService extends BaseService
{
    public function __construct(
        private readonly StockRepositoryInterface $stockRepository,
        private readonly ImageService $imageService,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * 商品を登録します。
     */
    public function save(CreateDto $dto): Stock
    {
        // 画像をアップロード
        $image = $this->imageService->store($dto->imageFile, ImageType::Stock, $dto->imageFileName);

        $stock = $this->stockRepository->create([
            'name'     => $dto->name,
            'detail'   => $dto->detail,
            'price'    => $dto->price,
            'quantity' => $dto->quantity,
            'image_id' => $image->id,
        ]);

        $this->operationLogService->logAdminAction(
            Auth::guard('admin')->id(),
            OperationLogType::AdminStockCreate,
            "商品を作成 (商品ID: {$stock->id}, 商品名: {$stock->name})",
            request()->ip()
        );

        return $stock;
    }
}
