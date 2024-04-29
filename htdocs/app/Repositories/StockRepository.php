<?php

namespace App\Repositories;

use App\Models\Stock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class StockRepository extends BaseRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return Stock::class;
    }

    /**
     * @param string|null $name
     * @param array<int, string>|array<string, mixed> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(string|null $name, array $options = []): Collection|LengthAwarePaginator
    {
        $query = $this->model
            ->with($this->__with($options))
            ->where('name', 'like', '%' . $name . '%')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'asc');

        $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
        return $limit > 0 ? $query->paginate($limit) : $query->get();
    }

    /**
     * @param array<int, string>|array<string, mixed> $options
     * @return array<int, string>
     */
    private function __with(array $options = [])
    {
        $with = [];
        if (!empty($options['with:orders'])) {
            $with[] = 'orders';
        }
        return $with;
    }

}
