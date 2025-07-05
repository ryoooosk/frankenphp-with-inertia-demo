<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelLevelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    // プロジェクトのPHPバージョンで推奨しているコードに強制
    ->withPhpSets()
    ->withPreparedSets(earlyReturn:true, privatization:true, typeDeclarations:true)
    ->withDeadCodeLevel(5)
    ->withCodeQualityLevel(5)
    // Laravelのバージョンに合わせたコードに強制
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_120,
    ])
    // 不使用のインポートを削除
    ->withImportNames(removeUnusedImports: true);
