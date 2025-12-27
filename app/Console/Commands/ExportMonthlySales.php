<?php

namespace App\Console\Commands;

use App\Services\Commands\ExportMonthlySalesService;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ExportMonthlySales extends BaseCommand
{
    protected $signature = 'export_monthly_sales
        {output_path : 出力ファイルパス}
        {--run : このオプションを指定した場合のみ本実行を行う(未指定時はドライラン)}';

    protected $description = '
        月別売上金額出力バッチ。
        ・月別売上データを取得する。
        ・引数で指定したパスにCSVファイルを出力する。
    ';

    public function handle(): int
    {
        $service = app(ExportMonthlySalesService::class);

        // 引数の入力チェック
        $args = array_merge($this->argument(), $this->option());
        $errors = $service->validateArgs($args);
        if (0 < count($errors)) {
            $this->error(implode("\n", $errors));

            return CommandAlias::INVALID;
        }

        // オプションの取得
        $outputPath = $this->argument('output_path');
        $this->isRealRun = $this->option('run');

        $this->outputLog(['処理が開始しました。pid[' . getmypid() . ']']);

        [$header, $detail] = $service->getCsvData();

        if ($this->isRealRun) {
            $file = fopen($outputPath, 'wb');
            // 先頭にBOMを追加
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $header, ',', '"');
            foreach ($detail as $row) {
                fputcsv($file, $row, ',', '"');
            }
            fclose($file);
        }

        $this->outputLog(["出力対象の月別売上データをCSV出力しました。[{$outputPath}]"]);
        $this->outputLog(['処理が終了しました。']);

        return CommandAlias::SUCCESS;
    }
}
