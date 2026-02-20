<?php

namespace Tests\Unit\Domain\Repositories\MonthlySale;

use App\Domain\Repositories\MonthlySale\MonthlySaleRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class MonthlySaleRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private MonthlySaleRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(MonthlySaleRepositoryInterface::class);
    }

    public function test_getAllOrderByYearMonth_データがない場合(): void
    {
        $result = $this->repository->getAllOrderByYearMonth();
        $this->assertSame(0, $result->count());
    }

    public function test_getAllOrderByYearMonth_年月の昇順で取得される(): void
    {
        $this->createDefaultMonthlySale(['year_month' => '202406', 'amount' => 30000]);
        $this->createDefaultMonthlySale(['year_month' => '202404', 'amount' => 10000]);
        $this->createDefaultMonthlySale(['year_month' => '202405', 'amount' => 20000]);

        $result     = $this->repository->getAllOrderByYearMonth();
        $yearMonths = $result->pluck('year_month')->all();

        $this->assertSame(['202404', '202405', '202406'], $yearMonths, '年月の昇順で取得されること');
    }
}
