<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class IndexService extends BaseService
{

    /**
     * @var AdminRepository
     */
    protected AdminRepository $adminRepository;

    public function __construct(
        Request $request,
        AdminRepository $adminRepository
    )
    {
        parent::__construct($request);
        $this->adminRepository = $adminRepository;
    }

    /**
     * @return LengthAwarePaginator<Admin>
     */
    public function searchStaff(): LengthAwarePaginator
    {
        $request = $this->request();
        $limit = 20;
        return $this->adminRepository->getByConditions([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'sort_name' => $request->sort_name ?? 'updated_at',
            'sort_direction' => $request->sort_direction ?? 'desc',
            'limit' => $limit,
        ]);
    }

}
