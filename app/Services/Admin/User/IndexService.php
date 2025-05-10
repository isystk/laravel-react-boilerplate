<?php

namespace App\Services\Admin\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use App\Dto\Request\Admin\User\SearchConditionDto;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexService extends BaseService
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * ユーザーを検索します。
     *
     * @return LengthAwarePaginator<int, User>
     */
    public function searchUser(SearchConditionDto $searchConditionDto): LengthAwarePaginator
    {
        $items = [
            'name' => $searchConditionDto->name,
            'email' => $searchConditionDto->email,
            'sort_name' => $searchConditionDto->sortName,
            'sort_direction' => $searchConditionDto->sortDirection,
            'limit' => $searchConditionDto->limit,
        ];

        return $this->userRepository->getByConditions($items);
    }
}
