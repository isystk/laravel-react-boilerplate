<?php

namespace Domain\Repositories\ImportHistory;

use App\Domain\Entities\ImportHistory;
use App\Domain\Repositories\ImportHistory\ImportHistoryRepository;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class ImportHistoryRepositoryTest extends TestCase
{

    use RefreshDatabase;

    private ImportHistoryRepository $repository;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ImportHistoryRepository::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(ImportHistoryRepository::class, $this->repository);
    }

    /**
     * getByImportHistoryのテスト
     */
    public function testGetByImportHistory(): void
    {
        $result = $this->repository->getByImportHistory(ImportType::Staff);
        $this->assertSame(0, $result->count(), 'データがない状態で正常に動作することを始めにテスト');

        /** @var ImportHistory $importHistory1 */
        $importHistory1 = ImportHistory::factory([
            'type' => ImportType::Staff->value,
        ])->create();
        /** @var ImportHistory $importHistory2 */
        $importHistory2 = ImportHistory::factory([
            'type' => ImportType::Staff->value,
        ])->create();
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

        ImportHistory::factory([
            'type' => ImportType::Staff->value,
            'status' => JobStatus::Success->value,
        ])->create();

        $result = $this->repository->hasProcessingByImportHistory(ImportType::Staff);
        $this->assertFalse($result, '処理中（または処理待ち）のデータが存在しない場合 → False');

        ImportHistory::factory([
            'type' => ImportType::Staff->value,
            'status' => JobStatus::Waiting->value,
        ])->create();
        ImportHistory::factory([
            'type' => ImportType::Staff->value,
            'status' => JobStatus::Processing->value,
        ])->create();

        $result = $this->repository->hasProcessingByImportHistory(ImportType::Staff);
        $this->assertTrue($result, '処理中（または処理待ち）のデータが存在する場合 → True');
    }
}
