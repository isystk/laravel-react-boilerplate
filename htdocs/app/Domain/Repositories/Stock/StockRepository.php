<?php

namespace App\Domain\Repositories\Stock;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface StockRepository extends BaseRepository
{
    /**
     * @param string|null $name
     * @param array<int, string>|array<string, mixed> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(string|null $name, array $options = []): Collection|LengthAwarePaginator;

}
