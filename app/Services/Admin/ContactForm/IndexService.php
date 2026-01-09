<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Dto\Request\Admin\ContactForm\SearchConditionDto;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexService extends BaseService
{
    public function __construct(private readonly ContactFormRepository $contactFormRepository) {}

    /**
     * お問い合わせを検索します。
     *
     * @return LengthAwarePaginator<int, ContactForm>
     */
    public function searchContactForm(SearchConditionDto $searchConditionDto): LengthAwarePaginator
    {
        $conditions = [
            'user_name'      => $searchConditionDto->userName,
            'title'          => $searchConditionDto->title,
            'sort_name'      => $searchConditionDto->sortName,
            'sort_direction' => $searchConditionDto->sortDirection,
            'limit'          => $searchConditionDto->limit,
        ];

        return $this->contactFormRepository->getByConditions($conditions);
    }
}
