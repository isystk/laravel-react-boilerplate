<?php

namespace App\Console\Commands;

use App\Enums\PhotoType;
use App\Services\Commands\PhotoS3UploadBatchService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class PhotoS3UploadBatch extends Command
{
    protected $signature = 's3upload
        {--run : このオプションを指定した場合のみ本実行を行う(未指定時はドライラン)}
        {--file_name= : 事業所番号(任意)}';
    protected $description = '
        S3アップロードバッチ。
        ・`resources/assets/stock/images` からファイルを取得する。
        ・S3の `stock` フォルダファイルをアップロードする。
    ';
    private bool $isRealRun;

    public function handle(): int
    {
        $service = app(PhotoS3UploadBatchService::class);

        // 引数の入力チェック
        $args = array_merge($this->argument(), $this->option());
        $errors = $service->validateArgs($args);
        if (0 < count($errors)) {
            $this->error(implode("\n", $errors));
            return CommandAlias::INVALID;
        }

        // オプションの取得
        $this->isRealRun = $this->option('run');
        $targetFileName  = $this->option('file_name');

        $this->outputLog(['処理が開始しました。pid[' . getmypid() . ']']);

        $files = Storage::files('stocks');
        // デバッグ追加
        if (empty($files)) {
            $this->error("ファイルが見つかりません。検索パス: " . Storage::path('stocks'));
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
                Storage::drive('s3')->putFileAs(
                    PhotoType::Stock->type(),
                    Storage::path($file),
                    $fileName
                );
            }

            $this->outputLog(["S3にアップロードしました。file={$file}"]);
        }

        $this->outputLog(['処理が終了しました。']);
        return CommandAlias::SUCCESS;
    }

    /**
     * 標準出力およびログファイルへの出力
     * @param array<string> $messages 出力メッセージの配列
     */
    private function outputLog(array $messages): void
    {
        foreach ($messages as $message) {
            $this->info($message);
            if (!$this->isRealRun) {
                continue;
            }
            $storage = Storage::drive('log');
            $dir = 'S3upload';
            if (!$storage->exists($dir)) {
                $storage->makeDirectory($dir);
                chmod($storage->path($dir), 0755);
            }
            $fileName = Carbon::now()->format('Ymd') . '.log';
            $storage->append($dir . '/' . $fileName, $message);
        }
    }
}
