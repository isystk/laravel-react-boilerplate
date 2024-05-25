<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class UpdateService extends BaseService
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
     * @param int $adminId
     * @return Admin
     */
    public function update(int $adminId): Admin
    {
        $request = $this->request();
        $model = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        return $this->adminRepository->update(
            $adminId,
            $model
        );
    }

}
