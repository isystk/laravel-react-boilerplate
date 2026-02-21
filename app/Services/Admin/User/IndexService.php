<?php

namespace App\Services\Admin\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Dto\Request\Admin\User\SearchConditionDto;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexService extends BaseService
{
    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    /**
     * ユーザーを検索します。
     *
     * @return LengthAwarePaginator<int, User>
     */
    public function searchUser(SearchConditionDto $searchConditionDto): LengthAwarePaginator
    {
        $items = [
            'keyword'        => $searchConditionDto->keyword,
            'status'         => $searchConditionDto->status,
            'has_google'     => $searchConditionDto->hasGoogle,
            'with_trashed'   => $searchConditionDto->withTrashed,
            'sort_name'      => $searchConditionDto->sortName,
            'sort_direction' => $searchConditionDto->sortDirection,
            'limit'          => $searchConditionDto->limit,
        ];

        return $this->userRepository->getByConditions($items);
    }
}
