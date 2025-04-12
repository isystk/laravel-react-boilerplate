<?php

namespace Services\Admin\Staff\Import;

use App\Domain\Entities\Admin;
use App\Domain\Entities\Stock;
use App\Services\Admin\Staff\Import\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportServiceTest extends TestCase
{

    use RefreshDatabase;

    private ExportService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ExportService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(ExportService::class, $this->service);
    }

    /**
     * getExportのテスト
     */
    public function testGetExport(): void
    {
        $export = $this->service->getExport();
        $this->assertSame(['ID', '名前', 'メールアドレス', '権限'], $export->headings(), 'ヘッダーが正しいこと');

        Admin::factory(['name' => 'admin1', 'email' => 'admin1@test.com', 'role' => 'high-manager'])->create();
        /** @var Admin $admin2 */
        $admin2 = Admin::factory(['name' => 'admin2', 'email' => 'admin2@test.com', 'role' => 'manager'])->create();

        $export = $this->service->getExport();
        $rows = $export->collection();

        $this->assertSame($admin2->id, $rows[1]["id"], '「ID」が正しく出力されること');
        $this->assertSame($admin2->name, $rows[1]["name"], '「名前」が正しく出力されること');
        $this->assertSame($admin2->email, $rows[1]["email"], '「メールアドレス」が正しく出力されること');
        $this->assertSame($admin2->role, $rows[1]["role"], '「権限」が正しく出力されること');
    }
}
