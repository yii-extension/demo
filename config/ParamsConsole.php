<?php

declare(strict_types=1);

namespace Yii;

use Yiisoft\Yii\Db\Migration\Command\CreateCommand;
use Yiisoft\Yii\Db\Migration\Command\ListTablesCommand;
use Yiisoft\Yii\Db\Migration\Command\DownCommand;
use Yiisoft\Yii\Db\Migration\Command\HistoryCommand;
use Yiisoft\Yii\Db\Migration\Command\NewCommand;
use Yiisoft\Yii\Db\Migration\Command\RedoCommand;
use Yiisoft\Yii\Db\Migration\Command\UpdateCommand;

final class ParamsConsole
{
    /**
     * Define console command customs console symphony.
     *
     * ```php
     * [
     *     'command' => App\Command::class
     * ]
     * ```
     *
     * @return array
     */
    public function getConsoleCommands(): array
    {
        return [
            'hellow' => \App\Command\Hellow::class,

            /** yii-db-migration commands */
            'generate/create' => CreateCommand::class,
            'database/list' => ListTablesCommand::class,
            'migrate/down' => DownCommand::class,
            'migrate/history' => HistoryCommand::class,
            'migrate/new' => NewCommand::class,
            'migrate/redo' => RedoCommand::class,
            'migrate/up' => UpdateCommand::class
        ];
    }

    public function getMigrationCreateNameSpace(): string
    {
        return 'Yii\\Migration';
    }

    public function getMigrationUpdateNameSpace(): array
    {
        return [
            'App\\Module\\User\\Migration',
            'App\\Module\\Rbac\\Migration'
        ];
    }

    public function getViewPath(): string
    {
        return dirname(__DIR__) . '/resources/views';
    }
}
