<?php

namespace App\Console\Commands;

use App\Domain\Entities\Stock;
use App\Services\Batch\StockService;
use App\Utils\CsvUtil;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class StockCSVBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stockcsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '商品の一覧をCSVファイルに出力します。';

    /**
     * Execute the console command.
     *
     */
    public function handle(): void
    {
        Log::info('StockCSVBatch START');

        /** @var StockService $service */
        $service = app(StockService::class);
        $stocks = $service->searchStock();

        $headers = ['ID', '商品名', '価格'];
        $rows = [];
        foreach ($stocks as $stock) {
            if (!$stock instanceof Stock) {
                throw new \RuntimeException('An unexpected error occurred.');
            }
            $row = [];
            $row[] = $stock->id;
            $row[] = $stock->name;
            $row[] = $stock->price;
            $rows[] = $row;
        }
        $csv = CsvUtil::make($rows, $headers);

        $time = new Carbon(Carbon::now());
        File::put('/tmp/stocks_' . $time->format('Ymd') . '.csv', $csv);
        Log::info('StockCSVBatch END');
    }
}
