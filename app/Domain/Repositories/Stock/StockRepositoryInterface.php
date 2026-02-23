<?php

namespace App\Domain\Repositories\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\BaseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface StockRepositoryInterface extends BaseRepositoryInterface
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

    /**
     * IDリストから商品データを取得します。
     *
     * @param  array<int>             $ids
     * @return Collection<int, Stock>
     */
    public function getByIds(array $ids): Collection;
}
