<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Enums\AdminRole;
use App\Http\Requests\Admin\Staff\StoreRequest;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;

class CreateService extends BaseService
{
    private AdminRepository $adminRepository;

    /**
     * Create a new controller instance.
     *
     * @param AdminRepository $adminRepository
     */
    public function __construct(
        AdminRepository $adminRepository
    ) {
        $this->adminRepository = $adminRepository;
    }

    /**
     * 管理者を登録します。
     * @param StoreRequest $request
     * @return Admin
     */
    public function save(StoreRequest $request): Admin
    {
        $model = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => AdminRole::Manager->value,
        ];
        return $this->adminRepository->create(
            $model
        );
    }

}
