<?php

namespace App\Console\Commands;

use App\Domain\Entities\MonthlySale;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ExportMonthlySales extends Command
{
    /**
     * The name and signature of the console command.
     * 例）php artisan export_poc_employees ./sample.csv
     * @var string
     */
    protected $signature = 'export_monthly_sales {output_path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '月別売上金額出力バッチ';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Start ExportMonthlySales. pid[' . getmypid() . ']');
        $outputPath = $this->argument('output_path');

        $header = $this->getHeader();
        $rows = $this->getDetail();

        $file = fopen($outputPath, 'wb');
        // 先頭にBOMを追加
        fwrite($file, "\xEF\xBB\xBF");
        fputcsv($file, $header, ',', '"');
        foreach ($rows as $row) {
            fputcsv($file, $row, ',', '"');
        }
        fclose($file);

        $this->info('Output succeeded. The number of records is ' . count($rows) . '. $outputPath');
        $this->info('End ExportMonthlySales. pid[' . getmypid() . ']');
    }

    /**
     * CSVファイルに出力するヘッダーを返却します。
     * @return string[]
     */
    private function getHeader(): array
    {
        return [
            '年月',
            '注文件数',
            '売上金額',
        ];
    }

    /**
     * CSVファイルに出力する内容を返却します。
     * @return string[][]
     */
    private function getDetail(): array
    {
        $monthlySales = $this->getMonthlySales();

        $rows = [];
        /** @var MonthlySale $monthlySale */
        foreach ($monthlySales as $monthlySale) {
            $row = [];
            $row[] = $monthlySale->year_month ?? ''; // 年月
            $row[] = (string)$monthlySale->order_count; // 注文件数
            $row[] = (string)$monthlySale->amount; // 売上金額
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * 出力対象の月別売上データを取得します。
     * @return Collection<MonthlySale>
     */
    private function getMonthlySales(): Collection
    {
        return MonthlySale::select()
            ->orderBy('year_month', 'desc')
            ->get();
    }

}
