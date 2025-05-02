<?php

namespace Console\Commands;

use App\Console\Commands\ExportMonthlySales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportMonthlySalesTest extends TestCase
{
    use RefreshDatabase;

    private ExportMonthlySales $sut;

    private string $output = 'test.csv';

    /**
     * 各テストの実行前に起動するメソッド
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->sut = app(ExportMonthlySales::class);
    }

    /**
     * 各テストの実行後に起動するメソッド
     */
    public function tearDown(): void
    {
        parent::tearDown();

        // 各関数で出力されたファイルを削除
        unlink($this->output);
    }

    /**
     * 出力データがない場合にヘッダーのみが出力されることをテスト
     */
    public function testHeaderOutputWithoutData(): void
    {
        // バッチ処理の実行
        $this->runBatch('export_monthly_sales', ['output_path' => $this->output]);
        // 出力されたCSVファイルを読み込む
        $rows = $this->readCsv($this->output);

        $method = $this->setPrivateMethodTest($this->sut, 'getHeader');
        $expectHeader = $method->invoke($this->sut);
        $this->assertCount(1, $rows);
        $this->assertSame($expectHeader, $rows[0], 'ヘッダーのみが出力されること');
    }

    /**
     * すべての値が想定通りに出力されることのテスト
     */
    public function testAllValues(): void
    {
        // 月別売上の作成
        $m1 = $this->createDefaultMonthlySale([
            'year_month' => "202404",
            'order_count' => 111,
            'amount' => 54321,
        ]);

        // バッチ処理の実行
        $this->runBatch('export_monthly_sales', ['output_path' => $this->output]);
        // 出力されたCSVファイルを読み込む
        $rows = $this->readCsv($this->output);
        // ヘッダーを除く
        array_shift($rows);

        $this->assertSame($m1->year_month, $rows[0][0], '「年月」が正しく出力されること');
        $this->assertSame((string)$m1->order_count, $rows[0][1], '「注文件数」が正しく出力されること');
        $this->assertSame((string)$m1->amount, $rows[0][2], '「売上金額」が正しく出力されること');
    }

    /**
     * 年月 の順で出力されることのテスト
     */
    public function testSortOrder(): void
    {
        // 月別売上の作成
        $m1 = $this->createDefaultMonthlySale([
            'year_month' => "202404",
            'order_count' => 222,
            'amount' => 54321,
        ]);
        $m2 = $this->createDefaultMonthlySale([
            'year_month' => "202405",
            'order_count' => 333,
            'amount' => 65432,
        ]);
        $m3 = $this->createDefaultMonthlySale([
            'year_month' => "202406",
            'order_count' => 111,
            'amount' => 76543,
        ]);

        // バッチ処理の実行
        $this->runBatch('export_monthly_sales', ['output_path' => $this->output]);
        // 出力されたCSVファイルを読み込む
        $rows = $this->readCsv($this->output);
        // ヘッダーを除く
        array_shift($rows);

        $expected = [
            $m3->year_month,
            $m2->year_month,
            $m1->year_month,
        ];
        $actual = [
            $rows[0][0],
            $rows[1][0],
            $rows[2][0],
        ];
        $this->assertSame($expected, $actual, '「年月」の順で出力されること');
    }

}
