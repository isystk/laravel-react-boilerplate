<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class IndexService extends BaseService
{
    /**
     * @var ContactFormRepository
     */
    protected ContactFormRepository $contactFormRepository;

    public function __construct(
        Request $request,
        ContactFormRepository $contactFormRepository
    )
    {
        parent::__construct($request);
        $this->contactFormRepository = $contactFormRepository;
    }

    /**
     * @return Collection|LengthAwarePaginator|array<string>
     */
    public function searchContactForm(): Collection|LengthAwarePaginator|array
    {
        $conditions = $this->convertConditionsFromRequest();
        return $this->contactFormRepository->getByConditions($conditions);
    }

    /**
     * リクエストパラメータから検索条件に変換します。
     * @return array{
     *   your_name : ?string,
     *   title : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : int,
     * }
     */
    private function convertConditionsFromRequest(): array
    {
        $limit = 20;
        $conditions = [
            'your_name' => null,
            'title' => null,
            'sort_name' => $this->request()->sort_name ?? 'updated_at',
            'sort_direction' => $this->request()->sort_direction ?? 'desc',
            'limit' => $limit,
        ];

        if (null !== $this->request()['userName']) {
            $conditions['your_name'] = $this->request()['userName'];
        }
        if (null !== $this->request()['title']) {
            $conditions['title'] = $this->request()['title'];
        }

        return $conditions;
    }
}
