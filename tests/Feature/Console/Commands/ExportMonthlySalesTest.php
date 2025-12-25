<?php

namespace Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command;
use Tests\BaseTest;

class ExportMonthlySalesTest extends BaseTest
{
    use RefreshDatabase;


    public function test_引数不正がある場合_エラー(): void
    {
        $command = $this->artisan('export_monthly_sales', [
            'output_path' => null,
        ]);

        $command->expectsOutput(implode("\n", [
            '出力ファイルパスを入力してください。',
        ]));
        $command->assertExitCode(Command::INVALID);
    }

    public function test_正常ケース(): void
    {
        Storage::fake();
        $fileName = 'test_monthly_sales.csv';
        $outputPath = Storage::path($fileName);

        $command = $this->artisan('export_monthly_sales', [
            'output_path' => $outputPath,
            '--run' => true,
        ]);
        $command->run();

        $command->expectsOutput("出力対象の月別売上データをCSV出力しました。[{$outputPath}]");
        Storage::assertExists($fileName);
        $command->assertSuccessful();
    }
}
