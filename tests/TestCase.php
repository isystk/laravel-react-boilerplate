<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    /**
     * 指定したコマンドとオプションでバッチ処理を実行します。
     * @param string $command
     * @param array<string, mixed> $options
     * @return void
     */
    protected function runBatch(string $command, array $options): void
    {
        // 標準出力を一時的に無効にする
        ob_start();

        // バッチ処理の実行
        $this->artisan($command, $options)
            ->assertExitCode(0);

        // 標準出力を無効にした状態を解除し、出力バッファを閉じる
        ob_end_clean();
    }
}
