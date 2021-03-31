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

        $files = Storage::disk('local')->files('app/public/stock');

        foreach ($files as $file) {
          #ファイル名
          $name = basename($file);
          #ファイルパス
          $path = storage_path($file);
          // //s3に画像をアップロード
          Storage::putFileAs('stock', new File($path), $name);
        }

        Log::info('PhotoS3UploadBatch END');
    }
}
