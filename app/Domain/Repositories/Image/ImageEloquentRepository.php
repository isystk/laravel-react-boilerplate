<?php

namespace App\Domain\Repositories\Image;

use App\Domain\Entities\Image;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ImageEloquentRepository extends BaseEloquentRepository implements ImageRepository
{
    protected function model(): string
    {
        return Image::class;
    }

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
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model->select('images.*')
            ->selectRaw('(SELECT id FROM stocks WHERE stocks.image_id = images.id) as used_by_stock_id')
            ->selectRaw('(SELECT id FROM contacts WHERE contacts.image_id = images.id) as used_by_contact_id')
            ->selectRaw('(SELECT id FROM users WHERE users.avatar_image_id = images.id) as used_by_user_id');

        if (!is_null($conditions['file_name'] ?? null)) {
            $query->where('file_name', 'like', '%' . $conditions['file_name'] . '%');
        }

        if (!is_null($conditions['file_type'] ?? null)) {
            $query->where('type', $conditions['file_type']);
        }

        if ($conditions['unused_only'] ?? false) {
            $query->whereRaw('NOT EXISTS(SELECT 1 FROM stocks WHERE stocks.image_id = images.id)')
                ->whereRaw('NOT EXISTS(SELECT 1 FROM contacts WHERE contacts.image_id = images.id)');
        }

        $sortColumn = $this->validateSortColumn(
            $conditions['sort_name'] ?? '',
            ['id', 'file_name', 'type', 'created_at', 'updated_at'],
        );
        if ($sortColumn !== null) {
            $query->orderBy($sortColumn, $conditions['sort_direction'] ?? 'asc');
        }
        // デフォルトの並び順を指定
        $query->orderBy('id', 'asc');

        if (!is_null($conditions['limit'] ?? null)) {
            /** @var LengthAwarePaginator<int, Image> */
            return $query->paginate($conditions['limit']);
        }

        /** @var Collection<int, Image> */
        return $query->get();
    }
}
