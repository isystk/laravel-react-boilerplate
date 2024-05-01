<?php

namespace App\Domain\Repositories\User;

use App\Domain\Entities\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface UserRepository extends BaseRepository
{
    /**
     * @param string|null $name
     * @param string|null $email
     * @param array<string, mixed> $options
     * @return Collection|LengthAwarePaginator|array<User>
     */
    public function findAll(?string $name, ?string $email, array $options = []): Collection|LengthAwarePaginator|array;
}
