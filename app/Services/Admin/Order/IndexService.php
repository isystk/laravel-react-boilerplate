<?php

namespace App\Services\Admin\Order;

use App\Domain\Entities\Order;
use App\Domain\Repositories\Order\OrderRepository;
use App\Dto\Request\Admin\Order\SearchConditionDto;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexService extends BaseService
{
    private OrderRepository $orderRepository;

    public function __construct(
        OrderRepository $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
    }

    /**
     * 注文情報を検索します。
     * @param SearchConditionDto $searchCondition
     * @return LengthAwarePaginator<int, Order>
     */
    public function searchOrder(SearchConditionDto $searchCondition): LengthAwarePaginator
    {
        $conditions = [
            'user_name' => $searchCondition->name,
            'order_date_from' => $searchCondition->orderDateFrom,
            'order_date_to' => $searchCondition->orderDateTo,
            'sort_name' => $searchCondition->sortName,
            'sort_direction' => $searchCondition->sortDirection,
            'limit' => $searchCondition->limit,
        ];
        return $this->orderRepository->getConditionsWithUserStock($conditions);
    }

}
