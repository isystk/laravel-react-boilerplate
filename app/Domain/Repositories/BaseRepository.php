<?php

namespace App\Domain\Repositories;

use Illuminate\Support\Collection;

interface BaseRepository
{
    /**
     * データを作成します。
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): mixed;

    /**
     * IDでデータを更新します。
     *
     * @param array<string, mixed> $data
     */
    public function update(array $data, int $id): mixed;

    /**
     * IDでデータを削除します。
     */
    public function delete(int $id): void;

    /**
     * IDでデータを復元します。
     */
    public function restore(int $id): void;

    /**
     * すべてのデータを取得します。
     *
     * @return Collection<int, mixed>
     */
    public function getAll(): Collection;

    /**
     * IDでデータを検索します。
     */
    public function findById(int $id): mixed;
}
