<?php

namespace App\Services\Jobs\Import;

use App\Domain\Repositories\Admin\AdminRepository;
use App\FileIO\Imports\BaseImport;
use App\Services\BaseService;
use Closure;

class StaffRegistService extends BaseService
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
     * 管理者を登録します。
     * @param int $importHistoryId
     * @param BaseImport $import
     * @param string $fileName
     * @param int $adminId
     * @param Closure $outputLog
     */
    public function exec(
        int $importHistoryId,
        BaseImport $import,
        string $fileName,
        int $adminId,
        Closure $outputLog
    ): void
    {
        // ファイルの中身をチェックする
        $rows = $import->array();

        $outputLog($fileName);
    }

}
