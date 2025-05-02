<?php

namespace Domain\Repositories\ImportHistory;

use App\Domain\Repositories\ImportHistory\ImportHistoryRepository;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportHistoryRepositoryTest extends TestCase
{

    use RefreshDatabase;

    private ImportHistoryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ImportHistoryRepository::class);
    }

    /**
     * getByImportHistoryのテスト
     */
    public function testGetByImportHistory(): void
    {
        $result = $this->repository->getByImportHistory(ImportType::Staff);
        $this->assertSame(0, $result->count(), 'データがない状態で正常に動作することを始めにテスト');

        $importHistory1 = $this->createDefaultImportHistory([
            'type' => ImportType::Staff->value,
        ]);
        $importHistory2 = $this->createDefaultImportHistory([
            'type' => ImportType::Staff->value,
        ]);
        $expected = [$importHistory1->id, $importHistory2->id];

        $result = $this->repository->getByImportHistory(ImportType::Staff);
        $actual = $result->pluck('id')->all();
        $this->assertSame($expected, $actual, '指定したImportTypeのデータが取得できることのテスト');
    }

    /**
     * hasProcessingByImportHistoryのテスト
     */
    public function testHasProcessingByImportHistory(): void
    {
        $result = $this->repository->hasProcessingByImportHistory(ImportType::Staff);
        $this->assertFalse($result, 'データがない状態で正常に動作することを始めにテスト');

        $this->createDefaultImportHistory([
            'type' => ImportType::Staff->value,
            'status' => JobStatus::Success->value,
        ]);

        $result = $this->repository->hasProcessingByImportHistory(ImportType::Staff);
        $this->assertFalse($result, '処理中（または処理待ち）のデータが存在しない場合 → False');

        $this->createDefaultImportHistory([
            'type' => ImportType::Staff->value,
            'status' => JobStatus::Waiting->value,
        ]);
        $this->createDefaultImportHistory([
            'type' => ImportType::Staff->value,
            'status' => JobStatus::Processing->value,
        ]);

        $result = $this->repository->hasProcessingByImportHistory(ImportType::Staff);
        $this->assertTrue($result, '処理中（または処理待ち）のデータが存在する場合 → True');
    }
}
