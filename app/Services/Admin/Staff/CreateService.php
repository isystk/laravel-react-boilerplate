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
    public function __construct(private readonly AdminRepository $adminRepository) {}

    /**
     * 管理者を登録します。
     */
    public function save(StoreRequest $request): Admin
    {
        return $this->adminRepository->create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => AdminRole::Manager,
        ]);
    }
}
