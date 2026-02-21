<?php

namespace App\Services\Admin\Stock;

use App\Domain\Repositories\Stock\StockRepositoryInterface;
use App\Enums\OperationLogType;
use App\Services\BaseService;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Auth;

class DestroyService extends BaseService
{
    public function __construct(
        private readonly StockRepositoryInterface $stockRepository,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * 商品を削除します。
     */
    public function delete(int $id): void
    {
        $this->stockRepository->delete($id);

        $this->operationLogService->logAdminAction(
            Auth::guard('admin')->id(),
            OperationLogType::AdminStockDelete,
            "商品を削除 (商品ID: {$id})",
            request()->ip()
        );
    }
}
