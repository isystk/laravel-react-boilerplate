<?php

namespace App\Services\Admin\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class IndexService extends BaseService
{
    private UserRepository $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * リクエストパラメータから検索条件に変換します。
     * @param Request $request
     * @param int $limit
     * @return array{
     *   name : ?string,
     *   email : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * }
     */
    public function convertConditionsFromRequest(Request $request, int $limit = 20): array
    {
        $conditions = [
            'name' => null,
            'email' => null,
            'role' => null,
            'sort_name' => $request->sort_name ?? 'updated_at',
            'sort_direction' => $request->sort_direction ?? 'desc',
            'limit' => $limit,
        ];

        if (null !== $request->name) {
            $conditions['name'] = $request->name;
        }
        if (null !== $request->email) {
            $conditions['email'] = $request->email;
        }

        return $conditions;
    }

    /**
     * ユーザーを検索します。
     * @param array{
     *   name : ?string,
     *   email : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * } $conditions
     * @return LengthAwarePaginator<User>
     */
    public function searchUser(array $conditions): LengthAwarePaginator
    {
        return $this->userRepository->getByConditions($conditions);
    }

}
