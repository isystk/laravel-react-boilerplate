<?php

namespace App\Services\Admin\Staff;

use App\Domain\Repositories\Admin\AdminRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class DestroyService extends BaseService
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
     * @param int $id
     */
    public function delete(int $id): void
    {
        // 管理者テーブルを削除
        $this->adminRepository->delete($id);
    }
}
