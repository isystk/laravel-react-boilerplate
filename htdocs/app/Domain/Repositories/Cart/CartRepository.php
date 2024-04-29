<?php

namespace App\Domain\Repositories\Cart;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface CartRepository extends BaseRepository
{
    /**
     * @param string $userId
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(string $userId, array $options = []): Collection|LengthAwarePaginator;
}
