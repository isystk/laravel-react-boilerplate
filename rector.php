<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use RectorLaravel\Rector\FuncCall\AppToResolveRector;
use RectorLaravel\Rector\StaticCall\CarbonToDateFacadeRector;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    // --- 解析対象パス ---
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/resources/views',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])

    // --- PHP バージョン設定 ---
    // PHP 8.2 の機能（readonly プロパティ、enum 等）に基づいたリファクタリングを有効化
    ->withPhpSets(php82: true)

    // --- ルールセットの設定 ---
    ->withSets([
        // Laravel 11 までのアップグレード
        LaravelLevelSetList::UP_TO_LARAVEL_110,
        // コード品質（不要なコードの削除や最適化）
        LaravelSetList::LARAVEL_CODE_QUALITY,
        // Collection メソッドの最適化
        LaravelSetList::LARAVEL_COLLECTION,
    ])

    // --- 除外設定（スキップするルール） ---
    ->withSkip([
        // Laravel のヘルパー関数で誤検知が多いため、余分な引数の削除を停止
        RemoveExtraParametersRector::class,
//
//        // Carbon から Date ファサードへの変換を停止
//        CarbonToDateFacadeRector::class,
//
//        // app() ヘルパー関数を resolve() に変換するルールを停止
//        AppToResolveRector::class,
    ])

    // --- 実行パフォーマンス設定 ---
    // 並列実行を有効にして処理時間を短縮
    ->withParallel()
    // 2回目以降の実行を高速化するためのキャッシュ設定
    ->withCache(
        cacheDirectory: __DIR__ . '/.rector_cache'
    );
