<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\User;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StudyCollection extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'study-collection';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Collectionの学習用です';

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
    Log::info('StudyCollection START');
    //

    $this->exec();

    Log::info('StudyCollection END');
  }

  /**
   * Write code on Method
   *
   * @return response()
   */
  public function exec()
  {
    // DBからEloquentで値を取得するとオブジェクトで返却される。
    $stocks = Stock::get();
    // 配列に変換するとデバックで参照しやすくなる。
    print_r($stocks->toArray());

    // 価格が10000円以上のものを1行ずつログで表示する
    $minPrice = 10000;
    $more10000 = $stocks->filter(function($item, $key) use($minPrice) {
        return ($item->price >= $minPrice);
    });
    // 1行ずつログで表示する
    $more10000->each(function($item, $key) {
        print_r($item->name .': '. $item->price . "\n");
    });

    // 商品名だけを抜き出してカンマ区切りで表示する
    $names = Stock::get()->pluck('name');
    print_r($names->join('、') . "\n");

    // 氏名から名字だけを抜き出して、重複しない値だけを取得する
    $users = User::get()->map(function($item, $key){
        return explode(" ", $item->name)[0];
    });
    $unique = $users->unique();
    print_r($unique->toArray());

  }
}
