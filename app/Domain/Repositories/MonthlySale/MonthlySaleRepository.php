<?php

namespace App\Domain\Repositories\MonthlySale;

use App\Domain\Entities\MonthlySale;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

interface MonthlySaleRepository extends BaseRepository
{

    /**
     * 年月の新しい順にすべてのレコードを返却する。
     * @return Collection<int, MonthlySale>
     */
    public function getAllOrderByYearMonthDesc(): Collection;
}
