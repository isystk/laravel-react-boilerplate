<?php

namespace App\Domain\Repositories\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface StockRepository extends BaseRepository
{
    /**
     * @return LengthAwarePaginator<int, Stock>
     */
    public function getByLimit(int $limit = 0): LengthAwarePaginator;

    /**
     * 検索条件からデータを取得します。
     *
     * @param array{
     *   name : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit ?: ?int,
     * } $conditions
     * @return Collection<int, Stock>|LengthAwarePaginator<int, Stock>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator;
}
