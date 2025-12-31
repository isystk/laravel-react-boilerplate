<?php

namespace Services\Admin\Staff\Import;

use App\Enums\AdminRole;
use App\Services\Admin\Staff\Import\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ExportServiceTest extends BaseTest
{
    use RefreshDatabase;

    private ExportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ExportService::class);
    }

    public function test_getExport(): void
    {
        $export = $this->service->getExport();
        $this->assertSame(['ID', '名前', 'メールアドレス', '権限'], $export->headings(), 'ヘッダーが正しいこと');

        $this->createDefaultAdmin(['name' => 'admin1', 'email' => 'admin1@test.com', 'role' => AdminRole::HighManager]);
        $admin2 = $this->createDefaultAdmin(['name' => 'admin2', 'email' => 'admin2@test.com', 'role' => AdminRole::Manager]);

        $export = $this->service->getExport();
        $rows = $export->collection();

        $this->assertSame($admin2->id, $rows[1]['id'], '「ID」が正しく出力されること');
        $this->assertSame($admin2->name, $rows[1]['name'], '「名前」が正しく出力されること');
        $this->assertSame($admin2->email, $rows[1]['email'], '「メールアドレス」が正しく出力されること');
        $this->assertSame($admin2->role->value, $rows[1]['role'], '「権限」が正しく出力されること');
    }
}
