<?php

declare(strict_types=1);

namespace Yii\Component;

use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;
use Yiisoft\Cache\Cache;
use Yiisoft\Cache\CacheInterface;
use Yiisoft\Cache\File\FileCache;
use Yiisoft\Factory\Definitions\Reference;

/** @var array $params */

return [
    CacheInterface::class => [
        '__class' => Cache::class,
        '__construct()' => [
            Reference::to(FileCache::class)
        ]
    ],

    SimpleCacheInterface::class => CacheInterface::class,
];
