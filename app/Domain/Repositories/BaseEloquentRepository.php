<?php

namespace App\Domain\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseEloquentRepository implements BaseRepository
{

    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->model());
    }

    /**
     * @param array<string, mixed> $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    /**
     * @param int $id
     * @param array<string, mixed> $data
     * @return mixed
     */
    public function update(int $id, array $data): mixed
    {
        $record = $this->findById($id);

        if (null === $record) {
            throw new \RuntimeException('An unexpected error occurred.');
        }

        $record->update($data);
        return $record;
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        $record = $this->findById($id);

        if (null === $record) {
            throw new \RuntimeException('An unexpected error occurred.');
        }

        $record->delete();
    }

    /**
     * @return Collection<int, mixed>
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * @return string
     */
    abstract protected function model(): string;

}
