<?php

namespace Tests\Unit\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Services\Admin\Stock\DownloadCsvService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadCsvServiceTest extends TestCase
{

    use RefreshDatabase;

    private DownloadCsvService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DownloadCsvService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(DownloadCsvService::class, $this->service);
    }

    /**
     * searchStockのテスト
     */
    public function testSearchStock(): void
    {
        $output = 'storage/test/test.csv';

        $default = [
            'name' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
            'limit' => 20,
        ];

        $csvData = $this->service->getCsvData($default);
        file_put_contents($output, $csvData);
        $rows = $this->readCsv($output);
        $method = $this->setPrivateMethodTest($this->service, 'getHeader');
        $expectHeader = $method->invoke($this->service);
        $this->assertSame($expectHeader, $rows[0], 'ヘッダーのみが出力されること');

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1', 'price' => 111])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2', 'price' => 222])->create();

        $input = $default;
        $input['name'] = 'stock2';
        $csvData = $this->service->getCsvData($input);
        file_put_contents($output, $csvData);
        $rows = $this->readCsv($output);
        // ヘッダーを除く
        array_shift($rows);
        $this->assertSame((string)$stock2->id, $rows[0][0], '「ID」が正しく出力されること');
        $this->assertSame($stock2->name, $rows[0][1], '「商品名」が正しく出力されること');
        $this->assertSame((string)$stock2->price, $rows[0][2], '「価格」が正しく出力されること');

        Storage::delete('test.csv');
    }
}
