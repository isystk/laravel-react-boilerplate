<?php

namespace App\Domain\Repositories\Order;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface OrderRepository extends BaseRepository
{
    /**
     * @param string|null $userName
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(?string $userName, array $options = []): Collection|LengthAwarePaginator;

}
