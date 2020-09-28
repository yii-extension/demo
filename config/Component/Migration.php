<?php

declare(strict_types=1);

namespace Yii\Component;

use Yii\ParamsConsole;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Yii\Db\Migration\Helper\ConsoleHelper;
use Yiisoft\Yii\Db\Migration\Service\MigrationService;

$params = new ParamsConsole();

return [
    /** component migration */
    MigrationService::class => [
        '__class' => MigrationService::class,
        '__construct()' => [
            Reference::to(ConnectionInterface::class),
            Reference::to(ConsoleHelper::class)
        ],
        'createNamespace()' => [$params->getMigrationCreateNameSpace()],
        'updateNamespace()' => [$params->getMigrationUpdateNameSpace()],
    ]
];
