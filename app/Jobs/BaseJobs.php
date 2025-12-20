<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

abstract class BaseJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 0; // タイムアウト秒数 (必ずretry_afterより小さい値にすること)

    public int $tries = 1; // 試行回数

    /**
     * ログファイルにメッセージを出力します
     */
    protected function outputLog(string $message): void
    {
        $jobName = class_basename(get_class($this));
        $dir = base_path() . '/storage/logs/' . $jobName . '/';
        // ディレクトリが無ければ作成する
        if (!file_exists($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
        if ($fp = fopen($dir . date('Ymd') . '.log', 'a+')) {
            $now = date('Y-m-d H:i:s');
            fwrite($fp, $now . ' ' . $message . "\n");
            fclose($fp);
        }
    }
}
