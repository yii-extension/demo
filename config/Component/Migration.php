<?php

declare(strict_types=1);

namespace Yii\Component;

use Psr\Container\ContainerInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Yii\Db\Migration\Helper\ConsoleHelper;
use Yiisoft\Yii\Db\Migration\Service\MigrationService;

return [
    /** component migration */
    MigrationService::class => static function (ContainerInterface $container) {
        $migrationCreateNameSpace = 'Yii\\Migration';
        $migrationUpdateNameSpace = [
            'App\\Module\\User\\Migration',
            'App\\Module\\Rbac\\Migration'
        ];

        $migration = new MigrationService(
            $container->get(ConnectionInterface::class),
            $container->get(ConsoleHelper::class)
        );

        $migration->createNamespace($migrationCreateNameSpace);
        $migration->updateNamespace($migrationUpdateNameSpace);

        return $migration;
    }
];
