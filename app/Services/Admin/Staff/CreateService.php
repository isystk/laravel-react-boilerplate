<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CreateService extends BaseService
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
     * @return Admin
     */
    public function save(): Admin
    {
        $request = $this->request();
        $model = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];
        return $this->adminRepository->create(
            $model
        );
    }

}
