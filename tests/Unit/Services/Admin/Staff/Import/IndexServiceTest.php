<?php

namespace Services\Admin\Staff\Import;

use App\Domain\Entities\Admin;
use App\Domain\Entities\ImportHistory;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use App\Services\Admin\Staff\Import\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexServiceTest extends TestCase
{

    use RefreshDatabase;

    private IndexService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(IndexService::class, $this->service);
    }

    /**
     * getImportHistoriesのテスト
     */
    public function testGetImportHistories(): void
    {
        $result = $this->service->getImportHistories();
        $this->assertSame([], $result, 'データがない状態でエラーにならないことを始めにテスト');

        /** @var Admin $admin */
        $admin = Admin::factory([
            'name' => 'admin1',
        ])->create();

        /** @var ImportHistory $importHistory */
        $importHistory = ImportHistory::factory([
            'type' => ImportType::Staff->value,
            'import_at' => '2024-06-01',
            'import_user_id' => $admin->id,
            'file_name' => 'test.csv',
            'status' => JobStatus::Success->value,
        ])->create();

        $result = $this->service->getImportHistories();
        $this->assertSame($importHistory->id, $result[0]['id'], '取得したidが想定通りであることのテスト');
        $this->assertSame(
            $importHistory->import_at->format('Y/m/d H:i:s'),
            $result[0]['import_at'],
            '取得したがimport_at想定通りであることのテスト'
        );
        $this->assertSame($admin->name, $result[0]['import_user_name'], '取得したimport_user_nameが想定通りであることのテスト');
        $this->assertSame($importHistory->file_name, $result[0]['file_name'], '取得したfile_nameが想定通りであることのテスト');
        $this->assertSame(JobStatus::Success->label(), $result[0]['status'], '取得したstatusが想定通りであることのテスト');
    }
}
