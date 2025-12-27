<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class BaseCommand extends Command
{
    protected $signature = 'basecommand';

    protected $description = 'base command description';

    /** 本実行フラグ */
    protected bool $isRealRun;

    /**
     * 標準出力およびログファイルへの出力
     *
     * @param  array<string>  $messages  出力メッセージの配列
     */
    protected function outputLog(array $messages): void
    {
        // 継承先の子クラス名をディレクトリ名として使用
        $dir = class_basename($this);

        foreach ($messages as $message) {
            $this->info($message);
            if (!$this->isRealRun) {
                continue;
            }
            $storage = Storage::disk('log');
            if (!$storage->exists($dir)) {
                $storage->makeDirectory($dir);
                chmod($storage->path($dir), 0755);
            }
            $fileName = Carbon::now()->format('Ymd') . '.log';
            $message = Carbon::now()->toDateTimeString() . ' ' . $message;
            $storage->append($dir . '/' . $fileName, $message);
        }
    }
}
