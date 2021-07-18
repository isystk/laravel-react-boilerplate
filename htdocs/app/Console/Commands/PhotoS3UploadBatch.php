<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PhotoS3UploadBatch extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 's3upload';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = '商品画像をS3にアップロードします。';

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
    Log::info('PhotoS3UploadBatch START');
    //

    // $path= public_path('assets/front/image/stock');
    // $files = File::allFiles($path);
    // Symfony\Component\Finder\SplFileInfo
    $storagePath = storage_path('app/public/stock');
    $files = \File::files($storagePath);
    foreach ($files as $file) {
      #ファイル名
      $name = $file->getfileName();
      #ファイルパス
      $path = $file->getpathName();
      // //s3に画像をアップロード
      Storage::putFileAs('stock', $file, $name);
    }

    Log::info('PhotoS3UploadBatch END');
  }
}
