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
     * データを作成します。
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    /**
     * データを更新します。
     *
     * @param array<string, mixed> $data
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
     * データを削除します。
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
     * データを復元します。
     */
    public function restore(int $id): void
    {
        $record = $this->model->withTrashed()->find($id);

        if ($record === null) {
            throw new \RuntimeException('An unexpected error occurred.');
        }

        $record->restore();
    }

    /**
     * 全件取得します。
     *
     * @return Collection<int, mixed>
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * IDでデータを検索します。
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
