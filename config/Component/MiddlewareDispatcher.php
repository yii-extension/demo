<?php

declare(strict_types=1);

namespace Yii\Component;

use Psr\Container\ContainerInterface;
use Yiisoft\Csrf\CsrfMiddleware;
use Yiisoft\Middleware\Dispatcher\MiddlewareStack;
use Yiisoft\Middleware\Dispatcher\MiddlewareStackInterface;
use Yiisoft\Middleware\Dispatcher\MiddlewareFactoryInterface;
use Yiisoft\Middleware\Dispatcher\MiddlewareFactory;
use Yiisoft\Router\Middleware\Router;
use Yiisoft\Session\SessionMiddleware;
use Yiisoft\Yii\Web\ErrorHandler\ErrorCatcher;
use Yiisoft\Yii\Web\MiddlewareDispatcher;

return [
    /** component middleware dispatcher */
    MiddlewareStackInterface::class => MiddlewareStack::class,

    MiddlewareFactoryInterface::class => MiddlewareFactory::class,

    MiddlewareDispatcher::class => static fn (ContainerInterface $container) => (new MiddlewareDispatcher($container))
        ->addMiddleware($container->get(Router::class))
        ->addMiddleware($container->get(SessionMiddleware::class))
        ->addMiddleware($container->get(CsrfMiddleware::class))
        ->addMiddleware($container->get(ErrorCatcher::class)),
];
