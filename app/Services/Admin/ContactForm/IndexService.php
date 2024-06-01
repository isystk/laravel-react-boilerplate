<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class IndexService extends BaseService
{

    private ContactFormRepository $contactFormRepository;

    /**
     * Create a new controller instance.
     *
     * @param ContactFormRepository $contactFormRepository
     */
    public function __construct(
        ContactFormRepository $contactFormRepository
    )
    {
        $this->contactFormRepository = $contactFormRepository;
    }

    /**
     * リクエストパラメータから検索条件に変換します。
     * @param Request $request
     * @param int $limit
     * @return array{
     *   user_name : ?string,
     *   title : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * }
     */
    public function convertConditionsFromRequest(Request $request, int $limit = 20): array
    {
        $conditions = [
            'user_name' => null,
            'title' => null,
            'sort_name' => $request->sort_name ?? 'updated_at',
            'sort_direction' => $request->sort_direction ?? 'desc',
            'limit' => $limit,
        ];

        if (null !== $request->userName) {
            $conditions['user_name'] = $request->userName;
        }
        if (null !== $request->title) {
            $conditions['title'] = $request->title;
        }

        return $conditions;
    }

    /**
     * お問い合わせを検索します。
     * @param array{
     *   user_name : ?string,
     *   title : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * } $conditions
     * @return Collection<int, ContactForm>|LengthAwarePaginator<ContactForm>|array<string>
     */
    public function searchContactForm(array $conditions): Collection|LengthAwarePaginator|array
    {
        return $this->contactFormRepository->getByConditions($conditions);
    }
}
