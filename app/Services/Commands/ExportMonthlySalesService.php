<?php

namespace App\Services\Commands;

use App\Domain\Entities\MonthlySale;
use App\Domain\Repositories\MonthlySale\MonthlySaleRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class ExportMonthlySalesService extends BaseService
{
    private MonthlySaleRepository $monthlySaleRepository;

    public function __construct(
        MonthlySaleRepository $monthlySaleRepository
    ) {
        $this->monthlySaleRepository = $monthlySaleRepository;
    }

    /**
     * 出力対象の月別売上データを取得します。
     *
     * @return Collection<int, MonthlySale>
     */
    public function getMonthlySales(): Collection
    {
        return $this->monthlySaleRepository->getAllOrderByYearMonthDesc();
    }
}
