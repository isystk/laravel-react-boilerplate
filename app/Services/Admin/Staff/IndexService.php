<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class IndexService extends BaseService
{

    private AdminRepository $adminRepository;

    /**
     * Create a new controller instance.
     *
     * @param AdminRepository $adminRepository
     */
    public function __construct(
        AdminRepository $adminRepository
    )
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * リクエストパラメータから検索条件に変換します。
     * @param Request $request
     * @param int $limit
     * @return array{
     *   name : ?string,
     *   email : ?string,
     *   role : ?string,
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
        if (null !== $request->role) {
            $conditions['role'] = $request->role;
        }

        return $conditions;
    }

    /**
     * 管理者を検索します。
     * @param array{
     *   name : ?string,
     *   email : ?string,
     *   role : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * } $conditions
     * @return LengthAwarePaginator<Admin>
     */
    public function searchStaff(array $conditions): LengthAwarePaginator
    {
        return $this->adminRepository->getByConditions($conditions);
    }

}
