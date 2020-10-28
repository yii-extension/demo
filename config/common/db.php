<?php

declare(strict_types=1);

use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Sqlite\Connection;

/** @var array $params */

return [
    ConnectionInterface::class => [
        '__class' => Connection::class,
        '__construct()' => [
            'dsn' => $params['yiisoft/db-sqlite']['dsn']
        ]
    ]
];
