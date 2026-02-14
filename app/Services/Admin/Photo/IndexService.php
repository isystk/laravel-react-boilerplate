<?php

namespace App\Services\Admin\Photo;

use App\Domain\Entities\Image;
use App\Domain\Repositories\Image\ImageRepository;
use App\Dto\Request\Admin\Photo\SearchConditionDto;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexService extends BaseService
{
    public function __construct(private readonly ImageRepository $imageRepository) {}

    /**
     * 画像を検索します。
     *
     * @return LengthAwarePaginator<int, Image>
     */
    public function searchPhotoList(SearchConditionDto $searchConditionDto): LengthAwarePaginator
    {
        $items = [
            'file_name'      => $searchConditionDto->fileName,
            'file_type'      => $searchConditionDto->fileType,
            'unused_only'    => $searchConditionDto->unusedOnly,
            'sort_name'      => $searchConditionDto->sortName,
            'sort_direction' => $searchConditionDto->sortDirection,
            'limit'          => $searchConditionDto->limit,
        ];

        return $this->imageRepository->getByConditions($items);
    }
}
