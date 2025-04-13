<?php

namespace App\Services\Admin\Order;

use App\Domain\Entities\Order;
use App\Domain\Repositories\Order\OrderRepository;
use App\Services\BaseService;
use App\Utils\DateUtil;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class IndexService extends BaseService
{
    private OrderRepository $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        OrderRepository $orderRepository
    )
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * リクエストパラメータから検索条件に変換します。
     * @param Request $request
     * @return array{
     *   user_name : ?string,
     *   order_date_from : ?CarbonImmutable,
     *   order_date_to : ?CarbonImmutable,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * }
     */
    public function convertConditionsFromRequest(Request $request): array
    {
        $limit = 20;
        $conditions = [
            'user_name' => $request->name,
            'order_date_from' => null,
            'order_date_to' => null,
            'sort_name' => $request->sort_name ?? 'updated_at',
            'sort_direction' => $request->sort_direction ?? 'desc',
            'limit' => $limit,
        ];

        $orderDateFrom = DateUtil::toCarbonImmutable($request->order_date_from);
        if (null !== $orderDateFrom) {
            $conditions['order_date_from'] = $orderDateFrom->startOfDay();
        }
        $orderDateTo = DateUtil::toCarbonImmutable($request->order_date_to);
        if (null !== $orderDateTo) {
            $conditions['order_date_to'] = $orderDateTo->startOfDay();
        }

        return $conditions;
    }

    /**
     * 注文情報を検索します。
     * @param array{
     *   user_name : ?string,
     *   order_date_from : ?CarbonImmutable,
     *   order_date_to : ?CarbonImmutable,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * } $conditions
     * @return Collection<int, Order>|LengthAwarePaginator<int, Order>
     */
    public function searchOrder(array $conditions): Collection|LengthAwarePaginator
    {
        return $this->orderRepository->getConditionsWithUserStock($conditions);
    }

}
