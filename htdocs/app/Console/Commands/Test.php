<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Test extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'test';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Laravelの動作テストです';

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
    Log::info('Test START');
    //

    $this->test();

    Log::info('Test END');
  }

  /**
   * Write code on Method
   *
   * @return response()
   */
  public function test()
  {
    $collection = collect([
        ['id' => 1, 'name' => '鈴木', 'age' => 20],
        ['id' => 2, 'name' => '佐藤', 'age' => 23],
        ['id' => 3, 'name' => '田中', 'age' => 20],
        ['id' => 4, 'name' => '山本', 'age' => 25],
        ['id' => 5, 'name' => '藤原', 'age' => 25],
    ]);
    $names = $collection->pluck('name');
    print_r($names->toArray());
   }
}
