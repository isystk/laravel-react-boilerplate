<?php

namespace Services\Admin\Stock;

use App\Dto\Request\Admin\Stock\SearchConditionDto;
use App\Services\Admin\Stock\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
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
        $request = new Request([
            'name' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
        ]);
        $default = new SearchConditionDto($request);

        $export = $this->service->getExport($default);
        $this->assertSame(['ID', '商品名', '価格'], $export->headings(), 'ヘッダーが正しいこと');

        $this->createDefaultStock(['name' => 'stock1', 'price' => 111]);
        $stock2 = $this->createDefaultStock(['name' => 'stock2', 'price' => 222]);

        $input = clone $default;
        $input->name = 'stock2';
        $export = $this->service->getExport($input);
        $rows = $export->collection();

        $this->assertSame($stock2->id, $rows[0]['id'], '「ID」が正しく出力されること');
        $this->assertSame($stock2->name, $rows[0]['name'], '「商品名」が正しく出力されること');
        $this->assertSame($stock2->price, $rows[0]['price'], '「価格」が正しく出力されること');
    }
}
