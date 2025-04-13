<?php

namespace App\Domain\Repositories;

use Illuminate\Support\Collection;

interface BaseRepository
{
    /**
     * @param array $data
     * @return mixed
     */
    // @phpstan-ignore-next-line
    public function create(array $data): mixed;

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    // @phpstan-ignore-next-line
    public function update(int $id, array $data): mixed;

    /**
     * @param int $id
     */
    public function delete(int $id): void;

    /**
     * @return Collection<int, mixed>
     */
    public function getAll(): Collection;

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed;

}
