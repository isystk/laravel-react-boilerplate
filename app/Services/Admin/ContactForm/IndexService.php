<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Dto\Request\Admin\ContactForm\SearchConditionDto;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class IndexService extends BaseService
{
    private ContactFormRepository $contactFormRepository;

    public function __construct(
        ContactFormRepository $contactFormRepository
    ) {
        $this->contactFormRepository = $contactFormRepository;
    }

    /**
     * お問い合わせを検索します。
     * @param SearchConditionDto $searchConditionDto
     * @return LengthAwarePaginator<int, ContactForm>
     */
    public function searchContactForm(SearchConditionDto $searchConditionDto): LengthAwarePaginator
    {
        $conditions = [
            'user_name' => $searchConditionDto->userName,
            'title' => $searchConditionDto->title,
            'sort_name' => $searchConditionDto->sortName,
            'sort_direction' => $searchConditionDto->sortDirection,
            'limit' => $searchConditionDto->limit,
        ];
        return $this->contactFormRepository->getByConditions($conditions);
    }
}
