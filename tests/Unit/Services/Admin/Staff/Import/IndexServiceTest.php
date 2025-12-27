<?php

namespace Services\Admin\Staff\Import;

use App\Enums\ImportType;
use App\Enums\JobStatus;
use App\Services\Admin\Staff\Import\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class IndexServiceTest extends BaseTest
{
    use RefreshDatabase;

    private IndexService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    public function test_getImportHistories(): void
    {
        $result = $this->service->getImportHistories();
        $this->assertSame([], $result, 'データがない状態でエラーにならないことを始めにテスト');

        $admin = $this->createDefaultAdmin([
            'name' => 'admin1',
        ]);

        $importHistory = $this->createDefaultImportHistory([
            'type' => ImportType::Staff->value,
            'import_at' => '2024-06-01 12:34:56',
            'import_user_id' => $admin->id,
            'file_name' => 'test.csv',
            'status' => JobStatus::Success->value,
        ]);

        $result = $this->service->getImportHistories();
        $this->assertSame($importHistory->id, $result[0]['id'], '取得したidが想定通りであることのテスト');
        $this->assertSame(
            '2024-06-01 12:34:56',
            $result[0]['import_at'],
            '取得したがimport_at想定通りであることのテスト'
        );
        $this->assertSame($admin->name, $result[0]['import_user_name'],
            '取得したimport_user_nameが想定通りであることのテスト');
        $this->assertSame($importHistory->file_name, $result[0]['file_name'],
            '取得したfile_nameが想定通りであることのテスト');
        $this->assertSame(JobStatus::Success->label(), $result[0]['status'],
            '取得したstatusが想定通りであることのテスト');
    }
}
