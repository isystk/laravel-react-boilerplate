<?php

namespace Services\Commands;

use App\Services\Commands\ExportMonthlySalesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ExportMonthlySalesServiceTest extends BaseTest
{
    use RefreshDatabase;

    private ExportMonthlySalesService $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = app(ExportMonthlySalesService::class);
    }

    public function test_validate_args(): void
    {
        $testCases = $this->getValidateArgsTestCases();
        foreach ($testCases as $key => $testCase) {
            $args = $testCase['args'];
            $expected = $testCase['expected'];

            $errors = $this->sut->validateArgs($args);
            $this->assertSame($expected, $errors);
        }
    }

    /**
     * validateArgs関数のテスト用データを返却する
     *
     * @return array<string, array{
     *     args: array<string, mixed>,
     *     expected: array<int, string>
     * }>
     */
    private function getValidateArgsTestCases(): array
    {
        $safeArgs = [
            'output_path' => '/var/www/html/storage/export/monthly_sales.csv',
        ];

        return [
            'OK : すべての正常な場合' => [
                'args' => $safeArgs,
                'expected' => [],
            ],
            'NG : 出力ファイルパスが未指定の場合' => [
                'args' => [...$safeArgs, 'output_path' => null],
                'expected' => [
                    '出力ファイルパスを入力してください。',
                ],
            ],
        ];
    }

    public function test_get_csv_data_出力データが存在しない場合(): void
    {
        [$header, $data] = $this->sut->getCsvData();

        $method = $this->setPrivateMethodTest($this->sut, 'getHeader');
        $expectHeader = $method->invoke($this->sut);
        $this->assertSame($expectHeader, $header, 'ヘッダーのみが出力されること');
        $this->assertCount(0, $data);
    }

    public function test_get_csv_data_出力データが存在する場合(): void
    {
        $m1 = $this->createDefaultMonthlySale([
            'year_month' => '202404',
            'order_count' => 222,
            'amount' => 54321,
        ]);
        $m2 = $this->createDefaultMonthlySale([
            'year_month' => '202405',
            'order_count' => 333,
            'amount' => 65432,
        ]);
        $m3 = $this->createDefaultMonthlySale([
            'year_month' => '202406',
            'order_count' => 111,
            'amount' => 76543,
        ]);

        [, $data] = $this->sut->getCsvData();

        $this->assertCount(3, $data);
        $expected = [
            $m3->year_month,
            $m2->year_month,
            $m1->year_month,
        ];
        $actual = [
            $data[0][0],
            $data[1][0],
            $data[2][0],
        ];
        $this->assertSame($expected, $actual, '「年月」の順で出力されること');
    }
}
