<?php

namespace App\Console\Commands;

use App\Enums\PhotoType;
use App\Services\Commands\PhotoUploadService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class PhotoUpload extends BaseCommand
{
    protected $signature = 'photo_upload
        {--run : このオプションを指定した場合のみ本実行を行う(未指定時はドライラン)}
        {--file_name= : 事業所番号(任意)}';

    protected $description = '
        S3アップロードバッチ。
        ・`resources/assets/stock/images` からファイルを取得する。
        ・S3の `stock` フォルダファイルをアップロードする。
    ';

    public function handle(): int
    {
        $service = app(PhotoUploadService::class);

        // 引数の入力チェック
        $args = array_merge($this->argument(), $this->option());
        $errors = $service->validateArgs($args);
        if (count($errors) > 0) {
            $this->error(implode("\n", $errors));

            return CommandAlias::INVALID;
        }

        // オプションの取得
        $this->isRealRun = $this->option('run');
        $targetFileName = $this->option('file_name');

        $this->outputLog(['処理が開始しました。pid[' . getmypid() . ']']);

        $files = Storage::files('stocks');
        // デバッグ追加
        if (empty($files)) {
            $this->error('ファイルが見つかりません。検索パス: ' . Storage::path('stocks'));

            return CommandAlias::FAILURE;
        }

        foreach ($files as $file) {
            $fileName = basename($file);

            // file_nameが指定されている場合は、ファイル名が一致するもののみ処理を行う
            if (!is_null($targetFileName) && $fileName !== $targetFileName) {
                continue;
            }

            if ($this->isRealRun) {
                // s3に画像をアップロード
                $result = Storage::disk('s3')->putFileAs(
                    PhotoType::Stock->type(),
                    Storage::path($file),
                    $fileName
                );
                if ($result === false) {
                    $this->outputLog(["S3アップロードに失敗しました。file={$file}"]);
                    continue;
                }
            }

            $this->outputLog(["S3にアップロードしました。file={$file}"]);
        }

        $this->outputLog(['処理が終了しました。']);

        return CommandAlias::SUCCESS;
    }
}
