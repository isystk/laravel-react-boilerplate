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

    public function test_正常ケース_ドライランの場合_ファイルが出力されないこと(): void
    {
        Storage::fake();
        $fileName = 'dry_run_sales.csv';
        $outputPath = Storage::path($fileName);

        // --run オプションを指定しない
        $this->artisan('export_monthly_sales', [
            'output_path' => $outputPath,
        ])
            ->expectsOutput("出力対象の月別売上データをCSV出力しました。[{$outputPath}]")
            ->assertSuccessful();

        // ファイルが作成されていないことを確認
        Storage::assertMissing($fileName);
    }

    public function test_正常ケース_データが存在する場合にCSV行が書き込まれること(): void
    {
        Storage::fake();
        $fileName = 'test_with_data.csv';
        $outputPath = Storage::path($fileName);

        // Serviceをモックして、必ずデータ（$detail）がある状態にする
        $this->mock(\App\Services\Commands\ExportMonthlySalesService::class, function ($mock) {
            $mock->shouldReceive('validateArgs')->andReturn([]);
            $mock->shouldReceive('getCsvData')->andReturn([
                ['Header1', 'Header2'], // $header
                [['Data1-1', 'Data1-2']], // $detail
            ]);
        });

        $this->artisan('export_monthly_sales', [
            'output_path' => $outputPath,
            '--run' => true,
        ])
            ->assertSuccessful();

        Storage::assertExists($fileName);

        // ファイルの中身にデータが含まれているか確認
        $content = Storage::get($fileName);
        $this->assertStringContainsString('Data1-1', $content);
    }
}
