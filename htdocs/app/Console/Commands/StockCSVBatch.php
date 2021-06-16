<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Services\CSVService;
use Carbon\Carbon;
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
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    Log::info('StockCSVBatch START');
    //
    $stocks = DB::table('stocks')->orderBy('id')->get();

    $csvHeader = ['ID', '商品名', '価格'];
    $csvBody = [];
    foreach ($stocks as $stock) {
      $line = [];
      $line[] = $stock->id;
      $line[] = $stock->name;
      $line[] = $stock->price;
      $csvBody[] = $line;
    }

    $csv = CSVService::make($csvBody, $csvHeader);

    $time = new Carbon(Carbon::now());
    $time->setToStringFormat('Ymd');
    File::put('./stocks_' . $time . '.csv', $csv);
    Log::info('StockCSVBatch END');
  }
}
