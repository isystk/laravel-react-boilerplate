<?php

namespace App\Domain\Repositories\MonthlySale;

use App\Domain\Entities\MonthlySale;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Support\Collection;

class MonthlySaleEloquentRepository extends BaseEloquentRepository implements MonthlySaleRepository
{
    protected function model(): string
    {
        return MonthlySale::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllOrderByYearMonthDesc(): Collection
    {
        /** @var Collection<int, MonthlySale> */
        return $this->model
            ->orderBy('year_month', 'desc')
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getAllOrderByYearMonth(): Collection
    {
        /** @var Collection<int, MonthlySale> */
        return $this->model
            ->orderBy('year_month')
            ->get(['year_month', 'amount']);
    }
}
