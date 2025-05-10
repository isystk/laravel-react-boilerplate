<?php

namespace App\Console\Commands;

use App\Enums\PhotoType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PhotoS3UploadBatch extends Command
{
    protected $signature = 's3upload';

    protected $description = '商品画像をS3にアップロードします。';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('PhotoS3UploadBatch START');

        $storagePath = storage_path('app/private/stock/images');
        $files = \File::files($storagePath);
        foreach ($files as $file) {
            // ファイル名
            $fileName = $file->getfileName();

            // s3に画像をアップロード
            Storage::putFileAs(PhotoType::Stock->type(), $file, $fileName);
        }

        Log::info('PhotoS3UploadBatch END');
    }
}
