<?php

declare(strict_types=1);

namespace Yii\Component;

use Psr\Log\LoggerInterface;
use Yii\Params;
use Yiisoft\Cache\CacheInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Sqlite\Connection;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Profiler\Profiler;

$params = new Params();

return [
    /** component db */
    ConnectionInterface::class => [
        '__class' => Connection::class,
        '__construct()' => [
            Reference::to(CacheInterface::class),
            Reference::to(LoggerInterface::class),
            Reference::to(Profiler::class),
            $params->getSqliteDsn()
        ]
    ]
];
