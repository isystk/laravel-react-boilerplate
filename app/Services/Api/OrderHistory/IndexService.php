<?php

namespace App\Services\Api\OrderHistory;

use App\Domain\Repositories\Order\OrderRepositoryInterface;
use App\Domain\Repositories\Order\OrderStockRepositoryInterface;
use App\Dto\Response\Api\OrderHistory\OrderDto;
use App\Dto\Response\Api\OrderHistory\SearchResultDto;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;

class IndexService extends BaseService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderStockRepositoryInterface $orderStockRepository
    ) {}

    /**
     * ログインユーザーの注文履歴を取得します。
     */
    public function getOrderHistory(): SearchResultDto
    {
        $user   = Auth::user();
        $orders = $this->orderRepository->getByUserId($user->id);

        $orderDtos = [];
        foreach ($orders as $order) {
            $orderStocks = $this->orderStockRepository->getByOrderId($order->id);
            // Stock情報をロードするためにRelationshipを使うか、Repositoryで取得する
            // OrderStockEntityにはstockリレーションがあることを確認済み
            foreach ($orderStocks as $orderStock) {
                $orderStock->load('stock');
            }
            $orderDtos[] = new OrderDto($order, $orderStocks->all());
        }

        return new SearchResultDto($orderDtos);
    }
}
