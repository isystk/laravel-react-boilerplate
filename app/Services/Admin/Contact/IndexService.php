<?php

namespace App\Services\Admin\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\Contact\ContactRepositoryInterface;
use App\Dto\Request\Admin\Contact\SearchConditionDto;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexService extends BaseService
{
    public function __construct(private readonly ContactRepositoryInterface $contactRepository) {}

    /**
     * お問い合わせを検索します。
     *
     * @return LengthAwarePaginator<int, Contact>
     */
    public function searchContact(SearchConditionDto $searchConditionDto): LengthAwarePaginator
    {
        $conditions = [
            'user_name'         => $searchConditionDto->userName,
            'title'             => $searchConditionDto->title,
            'contact_date_from' => $searchConditionDto->contactDateFrom,
            'contact_date_to'   => $searchConditionDto->contactDateTo,
            'sort_name'         => $searchConditionDto->sortName,
            'sort_direction'    => $searchConditionDto->sortDirection,
            'limit'             => $searchConditionDto->limit,
        ];

        return $this->contactRepository->getByConditions($conditions);
    }
}
