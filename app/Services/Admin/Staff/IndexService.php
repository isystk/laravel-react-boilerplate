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
        $limit = 20;
        return $this->adminRepository->getByConditions([
            'name' => $this->request()->name,
            'email' => $this->request()->email,
            'sort_name' => $this->request()->sort_name ?? 'updated_at',
            'sort_direction' => $this->request()->sort_direction ?? 'desc',
            'limit' => $limit,
        ]);
    }

}
