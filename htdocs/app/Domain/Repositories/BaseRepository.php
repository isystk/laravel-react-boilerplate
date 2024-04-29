<?php

namespace App\Domain\Repositories;

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
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed;

}
