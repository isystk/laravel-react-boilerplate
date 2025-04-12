<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

abstract class TestCase extends BaseTestCase
{

    /**
     * private protected 関数 をリフレクションでアクセス可能にする
     * @param object $target
     * @param string $methodName
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected function setPrivateMethodTest(object $target, string $methodName): ReflectionMethod
    {
        $reflection = new ReflectionClass($target);
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * private protected プロパティ をリフレクションでアクセス可能にする
     * @param object $target
     * @param string $propertyName
     * @return mixed
     * @throws ReflectionException
     */
    protected function getPrivateProperty(object $target, string $propertyName): mixed
    {
        $reflection = new ReflectionClass($target);
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($target);
    }

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

    /**
     * CSVファイルを読み込んで配列データを返却します。
     * @param string $path
     * @return array<array<string>>
     */
    protected function readCsv(string $path): array
    {
        $rows = [];
        $csvFile = fopen($path, 'rb');
        if ($csvFile === false) {
            return $rows;
        }

        // SJIS-winからUTF-8にエンコード
//        stream_filter_append($csvFile, 'convert.iconv.SJIS-win/UTF-8');
        // BOMを削除する
        $bom = pack('H*', 'EFBBBF');

        $first3bytes = fread($csvFile, 3);
        if ($first3bytes !== $bom) {
            rewind($csvFile);
        }

        while (($row = fgetcsv($csvFile)) !== false) {
            $rows[] = $row;
        }

        fclose($csvFile);
        return $rows;
    }
}
