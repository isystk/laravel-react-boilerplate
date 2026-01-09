<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    // 解析対象パス
    // Rector が自動リファクタリングを実行する対象ディレクトリ
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/resources/views',
        __DIR__ . '/routes',
        __DIR__ . '/database',
        __DIR__ . '/tests',
    ])

    // PHP 8.2 の機能を使用したリファクタリング
    // 例: readonly プロパティ、enum、Null Safe Operator など
    ->withPhpSets(php82: true)

    // 適用するルールセット
    ->withSets([
        // Laravel 11.0 までのアップグレードルール
        // 例: 古い書き方を新しい書き方に自動変換
        LaravelLevelSetList::UP_TO_LARAVEL_110,

        // Laravel コード品質向上ルール
        // 例: $array->map()->filter() を最適化
        LaravelSetList::LARAVEL_CODE_QUALITY,

        // Collection メソッドの最適化
        // 例: collect($array) を最適な形に変換
        LaravelSetList::LARAVEL_COLLECTION,
    ])
    // スキップするルール（適用しない）
    // 誤検知や意図的に保持したいコードパターンを除外
    ->withSkip([
        // メソッドの余分なパラメータを削除するルール
        // Laravel のヘルパー関数で誤検知が多いためスキップ
        RemoveExtraParametersRector::class,
    ])

    // 並列実行で高速化
    // CPU コア数に応じて並列処理を行い、実行時間を短縮
    ->withParallel()

    // キャッシュ設定
    // 変更されていないファイルをスキップし、2回目以降の実行を高速化
    ->withCache(
        cacheDirectory: __DIR__ . '/.rector_cache'
    );
