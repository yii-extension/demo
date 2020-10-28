<?php

declare(strict_types=1);

namespace Yii\Component;

use Yiisoft\Cache\Cache;
use Yiisoft\Cache\CacheInterface;
use Yiisoft\Cache\File\FileCache;
use Yiisoft\Factory\Definitions\Reference;

return [
    /** component cache */
    CacheInterface::class => [
        '__class' => Cache::class,
        '__construct()' => [
            Reference::to(FileCache::class)
        ]
    ]
];
