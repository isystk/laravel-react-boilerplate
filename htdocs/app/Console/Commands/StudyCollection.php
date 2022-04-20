<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use App\Models\Stock;
use App\Models\User;
use App\Models\ContactForm;
use App\Enums\Gender;

use Illuminate\Console\Command;

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
    // 確認したいSQLの前にこれを仕込むとSQLの実行結果が確認できる。
    \Illuminate\Support\Facades\DB::enableQueryLog();
    // DBからEloquentで値を取得する（返り値は、get → Collection、findとfirst → Modelのオブジェクト）
    $stocks = Stock::get();
    print_r(\Illuminate\Support\Facades\DB::getQueryLog());

    // Webの場合はログファイルに出力すると確認できる
    \Illuminate\Support\Facades\Log::debug(\Illuminate\Support\Facades\DB::getQueryLog());

    // 配列に変換するとデバックで参照しやすくなる。
    print_r($stocks->toArray());

    // 価格が10000円以上のものを1行ずつログで表示する
    $minPrice = 10000;
    $more10000 = ($stocks ?? collect([]))->filter(function($item, $key) use($minPrice) {
        return ($item->price >= $minPrice);
    });
    // 1行ずつログで表示する
    $more10000->each(function($item, $key) {
        print_r($item->name .': '. $item->price . "\n");
    });

    // 商品名だけを抜き出してカンマ区切りで表示する
    $names = ($stocks ?? collect([]))->pluck('name');
    print_r($names->join('、') . "\n");

    // 商品IDをキーにしたMapを作成する
    $stockMap = ($stocks ?? collect([]))->mapWithKeys(function($stock) {
        return [$stock['id'] => $stock];
    });
    print_r($stockMap['1'] . "\n");

    // 氏名から名字だけを抜き出して、重複しない値だけを取得する
    $users = User::get()->map(function($item, $key){
        return explode(" ", $item->name)[0];
    });
    $unique = ($users ?? collect([]))->unique();
    print_r($unique->toArray());

    // お問い合わせを男性と女性でグループ化して取得する
    $contacts = ContactForm::get()->groupBy('gender');
    ($contacts ?? collect([]))->each(function($contact, $key) {
        print_r(Gender::getDescription($key) . '->' . $contact->count() . '人' . "\n");
    });

    // // JSONファイルを出力
    // file_put_contents("test.json" , json_encode($unique));
  }
}
