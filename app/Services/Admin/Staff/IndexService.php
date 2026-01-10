<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexService extends BaseService
{
    public function __construct(private readonly AdminRepository $adminRepository) {}

    /**
     * リクエストパラメータから検索条件に変換します。
     *
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
            'name'           => null,
            'email'          => null,
            'role'           => null,
            'sort_name'      => $request->sort_name ?? 'updated_at',
            'sort_direction' => $request->sort_direction ?? 'desc',
            'limit'          => $limit,
        ];

        if ($request->name !== null) {
            $conditions['name'] = $request->name;
        }
        if ($request->email !== null) {
            $conditions['email'] = $request->email;
        }
        if ($request->role !== null) {
            $conditions['role'] = $request->role;
        }

        return $conditions;
    }

    /**
     * 管理者を検索します。
     *
     * @param array{
     *   name : ?string,
     *   email : ?string,
     *   role : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * } $conditions
     * @return LengthAwarePaginator<int, Admin>
     */
    public function searchStaff(array $conditions): LengthAwarePaginator
    {
        return $this->adminRepository->getByConditions($conditions);
    }
}
