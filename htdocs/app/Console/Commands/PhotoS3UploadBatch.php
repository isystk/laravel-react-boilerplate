<?php

namespace App\Console\Commands;

use App\Enums\PhotoType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
     * Execute the console command.
     *
     */
    public function handle(): void
    {
        Log::info('PhotoS3UploadBatch START');

        $storagePath = storage_path('app/stock');
        $files = \File::files($storagePath);
        foreach ($files as $file) {
            // ファイル名
            $fileNmae = $file->getfileName();

            // s3に画像をアップロード
            Storage::putFileAs(PhotoType::Stock->dirName(), $file, $fileNmae);
        }

        Log::info('PhotoS3UploadBatch END');
    }
}
