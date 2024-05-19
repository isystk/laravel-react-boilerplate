<?php

namespace App\Services\Admin\Order;

use App\Domain\Entities\Order;
use App\Domain\Repositories\Order\OrderRepository;
use App\Services\BaseService;
use App\Utils\DateUtil;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class IndexService extends BaseService
{
    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    public function __construct(
        Request $request,
        OrderRepository $orderRepository
    )
    {
        parent::__construct($request);
        $this->orderRepository = $orderRepository;
    }

    /**
     * @return Collection<int, Order>|LengthAwarePaginator<Order>|array<string>
     */
    public function searchOrder(): Collection|LengthAwarePaginator|array
    {
        $limit = 20;
        return $this->orderRepository->getConditionsWithUserStock([
            'user_name' => $this->request()->name,
            'order_date_from' => DateUtil::toCarbonImmutable($this->request()->order_date_from)?->startOfDay(),
            'order_date_to' => DateUtil::toCarbonImmutable($this->request()->order_date_to)?->endOfDay(),
            'sort_name' => $this->request()->sort_name ?? 'created_at',
            'sort_direction' => $this->request()->sort_direction ?? 'desc',
            'limit' => $limit,
        ]);
    }

}
