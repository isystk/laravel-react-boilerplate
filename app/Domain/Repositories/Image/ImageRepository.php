<?php

namespace App\Domain\Repositories\Image;

use App\Domain\Entities\Image;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ImageRepository extends BaseRepository
{
    /**
     * 検索条件からデータを取得します。
     *
     * @param array{
     *   file_name: ?string,
     *   file_type: ?int,
     *   unused_only: bool,
     *   sort_name: ?string,
     *   sort_direction: 'asc'|'desc'|null,
     *   limit?: ?int,
     * } $conditions
     * @return Collection<int, Image>|LengthAwarePaginator<int, Image>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator;
}
