<?php

namespace App\Domain\Repositories;

use Illuminate\Support\Collection;

interface BaseRepository
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): mixed;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(array $data, int $id): mixed;

    public function delete(int $id): void;

    /**
     * @return Collection<int, mixed>
     */
    public function getAll(): Collection;

    public function findById(int $id): mixed;
}
