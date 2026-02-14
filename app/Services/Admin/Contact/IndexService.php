<?php

namespace App\Services\Admin\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\Contact\ContactRepository;
use App\Dto\Request\Admin\Contact\SearchConditionDto;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexService extends BaseService
{
    public function __construct(private readonly ContactRepository $contactRepository) {}

    /**
     * お問い合わせを検索します。
     *
     * @return LengthAwarePaginator<int, Contact>
     */
    public function searchContact(SearchConditionDto $searchConditionDto): LengthAwarePaginator
    {
        $conditions = [
            'user_name'      => $searchConditionDto->userName,
            'title'          => $searchConditionDto->title,
            'sort_name'      => $searchConditionDto->sortName,
            'sort_direction' => $searchConditionDto->sortDirection,
            'limit'          => $searchConditionDto->limit,
        ];

        return $this->contactRepository->getByConditions($conditions);
    }
}
