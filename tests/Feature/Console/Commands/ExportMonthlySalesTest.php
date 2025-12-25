<?php

namespace Console\Commands;

use App\Console\Commands\ExportMonthlySales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ExportMonthlySalesTest extends BaseTest
{
    use RefreshDatabase;

    private ExportMonthlySales $sut;

    private string $output = 'test.csv';

    /**
     * 各テストの実行前に起動するメソッド
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = app(ExportMonthlySales::class);
    }

    /**
     * 各テストの実行後に起動するメソッド
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

}
