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
     * {@inheritDoc}
     */
    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function update(array $data, int $id): mixed
    {
        $record = $this->findById($id);

        if ($record === null) {
            throw new \RuntimeException('An unexpected error occurred.');
        }

        $record->update($data);

        return $record;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(int $id): void
    {
        $record = $this->findById($id);

        if ($record === null) {
            throw new \RuntimeException('An unexpected error occurred.');
        }

        $record->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function restore(int $id): void
    {
        // @phpstan-ignore-next-line method.notFound
        $record = $this->model->withTrashed()->find($id);

        if ($record === null) {
            throw new \RuntimeException('An unexpected error occurred.');
        }

        $record->restore();
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * {@inheritDoc}
     */
    public function findById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * ソートカラム名が許可リストに含まれるか検証し、安全なカラム名を返します。
     *
     * @param array<int, string> $allowedColumns
     */
    protected function validateSortColumn(string $sortName, array $allowedColumns): ?string
    {
        return in_array($sortName, $allowedColumns, true) ? $sortName : null;
    }

    abstract protected function model(): string;
}
