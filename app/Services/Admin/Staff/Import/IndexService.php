<?php

namespace App\Services\Admin\Staff\Import;

use App\Domain\Entities\Admin;
use App\Domain\Entities\ImportHistory;
use App\Domain\Repositories\ImportHistory\ImportHistoryRepository;
use App\Enums\ImportType;
use App\Services\BaseService;

class IndexService extends BaseService
{
    public function __construct(private readonly ImportHistoryRepository $importHistoryRepository) {}

    /**
     * 受理ファイルインポート履歴を取得します。
     *
     * @return array<int, array<string, int|string>>
     */
    public function getImportHistories(): array
    {
        // インポート履歴を取得
        $importHistories = $this->importHistoryRepository->getByImportHistory(ImportType::Staff);

        $importHistories = $importHistories->map(function (ImportHistory $importHistory) {
            $admin = Admin::find($importHistory->import_user_id);

            // インポート履歴を表示用に加工
            return [
                'id'               => $importHistory->id,
                'import_at'        => $importHistory->import_at->toDateTimeString(),
                'import_user_name' => $admin->name,
                'file_name'        => $importHistory->file_name,
                'status'           => $importHistory->status->label(),
            ];
        });

        return $importHistories->all();
    }
}
