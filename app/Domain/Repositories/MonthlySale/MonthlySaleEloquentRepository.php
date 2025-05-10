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
     * 年月の新しい順にすべてのレコードを返却する。
     * @return Collection<int, MonthlySale>
     */
    public function getAllOrderByYearMonthDesc(): Collection
    {
        /** @var Collection<int, MonthlySale> */
        return $this->model
            ->orderBy('year_month', 'desc')
            ->get();
    }
}
