<?php

namespace App\Domain\Repositories\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class StockEloquentRepository extends BaseEloquentRepository implements StockRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return Stock::class;
    }

    /**
     * 指定した件数のデータを最新順に取得します。
     * @param int $limit
     * @return LengthAwarePaginator<int, Stock>
     */
    public function getByLimit(int $limit = 0): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator<int, Stock> */
        return $this->model
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }

    /**
     * 検索条件からデータを取得します。
     * @param array{
     *   name : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, Stock>|LengthAwarePaginator<int, Stock>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model->select();

        if (null !== $conditions['name']) {
            $query->where('name', 'like', '%' . $conditions['name'] . '%');
        }

        if (null !== $conditions['sort_name']) {
            $query->orderBy($conditions['sort_name'], $conditions['sort_direction'] ?? 'asc');
        }

        return null !== $conditions['limit'] ? $query->paginate($conditions['limit']) : $query->get();
    }

}
