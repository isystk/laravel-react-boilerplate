<?php

namespace App\Services\Admin\Staff\Import;

use App\Domain\Repositories\Admin\AdminRepository;
use App\FileIO\Exports\StaffExport;
use App\Services\BaseService;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportService extends BaseService
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
     * 管理者をCSVでエクスポートします。
     */
    public function exportCsv(): BinaryFileResponse
    {
        $admins = $this->adminRepository->getAllOrderById();
        return Excel::download(new StaffExport($admins), 'staff.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * 管理者をExcelでエクスポートします。
     */
    public function exportExcel(): BinaryFileResponse
    {
        $admins = $this->adminRepository->getAllOrderById();
        return Excel::download(new StaffExport($admins), 'staff.xlsx');
    }

}
