<?php

declare(strict_types=1);

use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Sqlite\Connection;

return [
    /** component db */
    ConnectionInterface::class => [
        '__class' => Connection::class,
        '__construct()' => [
            'dsn' => $params['yiisoft/db-sqlite']['dsn']
        ]
    ]
];
