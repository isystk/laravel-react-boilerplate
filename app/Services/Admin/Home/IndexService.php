<?php

namespace App\Services\Admin\Home;

use App\Domain\Repositories\MonthlySale\MonthlySaleRepository;
use App\Domain\Repositories\Order\OrderRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class IndexService extends BaseService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly MonthlySaleRepository $monthlySaleRepository,
    ) {}

    /**
     * 本日の注文件数を取得する。
     */
    public function getTodaysOrdersCount(): int
    {
        return $this->orderRepository->countTodaysOrders();
    }

    /**
     * 月次売上データを取得する。
     *
     * @return Collection<int, array{year_month: string, amount: int}>
     */
    public function getSalesByMonth(): Collection
    {
        return $this->monthlySaleRepository->getAllOrderByYearMonth()
            ->map(fn ($m) => [
                'year_month' => $m->year_month,
                'amount'     => (int) ($m->amount ?? 0),
            ])
            ->values();
    }

    /**
     * 最新の注文を取得する。
     *
     * @return Collection<int, \App\Domain\Entities\Order>
     */
    public function getLatestOrders(int $limit = 10): Collection
    {
        return $this->orderRepository->getLatestWithUser($limit);
    }
}
