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
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(array $data, int $id): mixed
    {
        $record = $this->findById($id);

        if (null === $record) {
            throw new \RuntimeException('An unexpected error occurred.');
        }

        $record->update($data);

        return $record;
    }

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

    public function findById(int $id): mixed
    {
        return $this->model->find($id);
    }

    abstract protected function model(): string;
}
