<?php

namespace App\Services\Admin\Home;

use App\Domain\Repositories\Contact\ContactRepositoryInterface;
use App\Domain\Repositories\MonthlySale\MonthlySaleRepositoryInterface;
use App\Domain\Repositories\Order\OrderRepositoryInterface;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class IndexService extends BaseService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly MonthlySaleRepositoryInterface $monthlySaleRepository,
        private readonly ContactRepositoryInterface $contactRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    /**
     * 本日の注文件数を取得する。
     */
    public function getTodaysOrdersCount(): int
    {
        return $this->orderRepository->countTodaysOrders();
    }

    /**
     * 未返信のお問い合わせ件数を取得する。
     */
    public function getUnrepliedContactsCount(): int
    {
        return $this->contactRepository->countUnreplied();
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

    /**
     * 月別の新規ユーザー数を取得する。
     *
     * @return Collection<int, array{year_month: string, count: int}>
     */
    public function getUsersByMonth(int $months = 12): Collection
    {
        $users = $this->userRepository->countByMonth($months);

        return $users->map(fn (object $row) => [
            'year_month' => (string) $row->year_month,
            'count'      => (int) $row->count,
        ])->values();
    }
}
