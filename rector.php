<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    // 1. リファクタリング対象のディレクトリを指定
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    // 2. 適用するルールセットを指定
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_110, // お使いのバージョンに合わせて調整
        LaravelSetList::LARAVEL_CODE_QUALITY,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
    ])
    // 3. 準備が整ったらここを有効にすると、インポート（use文）が整理されます
    ->withImportNames();
