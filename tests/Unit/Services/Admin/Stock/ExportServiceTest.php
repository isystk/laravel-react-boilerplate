<?php

namespace Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Services\Admin\Stock\ExportService;
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
     * getExportのテスト
     */
    public function testGetExport(): void
    {
        $default = [
            'name' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
        ];

        $export = $this->service->getExport($default);
        $this->assertSame(['ID', '商品名', '価格'], $export->headings(), 'ヘッダーが正しいこと');

        Stock::factory(['name' => 'stock1', 'price' => 111])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2', 'price' => 222])->create();

        $input = $default;
        $input['name'] = 'stock2';
        $export = $this->service->getExport($input);
        $rows = $export->collection();

        $this->assertSame($stock2->id, $rows[0]["id"], '「ID」が正しく出力されること');
        $this->assertSame($stock2->name, $rows[0]["name"], '「商品名」が正しく出力されること');
        $this->assertSame($stock2->price, $rows[0]["price"], '「価格」が正しく出力されること');
    }
}
