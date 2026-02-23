<?php

namespace App\Services\Admin\Photo;

use App\Domain\Entities\Image;
use App\Domain\Repositories\Image\ImageRepositoryInterface;
use App\Dto\Request\Admin\Photo\SearchConditionDto;
use App\Dto\View\Admin\Photo\DisplayDto;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexService extends BaseService
{
    public function __construct(private readonly ImageRepositoryInterface $imageRepository) {}

    /**
     * 画像を検索します。
     *
     * @return LengthAwarePaginator<int, DisplayDto>
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

        /** @var LengthAwarePaginator<int, Image> $images */
        $images = $this->imageRepository->getByConditions($items);

        /** @var \Illuminate\Support\Collection<int, Image> $mappedCollection */
        $mappedCollection = $images->getCollection()->map(fn (Image $image) => new DisplayDto($image));

        /** @var LengthAwarePaginator<int, DisplayDto> $result */
        $result = $images->setCollection($mappedCollection);

        return $result;
    }
}
