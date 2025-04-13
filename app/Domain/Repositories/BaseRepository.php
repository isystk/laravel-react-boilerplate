<?php

namespace App\Domain\Repositories;

use Illuminate\Support\Collection;

interface BaseRepository
{
    /**
     * @param array<string, mixed> $data
     * @return mixed
     */
    public function create(array $data): mixed;

    /**
     * @param int $id
     * @param array<string, mixed> $data
     * @return mixed
     */
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
