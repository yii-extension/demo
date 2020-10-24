<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Tester\CommandTester;
use App\Tests\UnitTester;
use Yiisoft\Composer\Config\Builder;
use Yiisoft\Di\Container;

final class HelloCommandCest
{
    private ContainerInterface $container;

    public function _before(UnitTester $I): void
    {
        $this->container = new Container(
            require Builder::path('web-local')
        );
    }

    public function testExecute(UnitTester $I): void
    {
        $app = new Application();
        $paramsConsole = require Builder::path('params-console-local');

        $loader = new ContainerCommandLoader(
            $this->container,
            $paramsConsole['consoleCommands']
        );

        $app->setCommandLoader($loader);

        $command = $app->find('hello');

        $commandCreate = new CommandTester($command);

        $commandCreate->setInputs(['yes']);

        $I->assertEquals(1, $commandCreate->execute([]));

        $output = $commandCreate->getDisplay(true);

        $I->assertStringContainsString('Hello Command', $output);
    }
}
