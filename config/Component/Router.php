<?php

declare(strict_types=1);

namespace Yii\Component;

use App\Module\User\Config\Routes as UserRoutes;
use App\Module\Rbac\Config\Routes as RbacRoutes;
use Psr\Container\ContainerInterface;
use Yii\Routes;
use Yiisoft\DataResponse\Middleware\FormatDataResponse;
use Yiisoft\Router\Group;
use Yiisoft\Router\Dispatcher;
use Yiisoft\Router\DispatcherInterface;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\RouteCollectionInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\Router\FastRoute\UrlGenerator;
use Yiisoft\Router\FastRoute\UrlMatcher;

return [
    /** component router */
    UrlMatcherInterface::class => UrlMatcher::class,

    UrlGeneratorInterface::class => UrlGenerator::class,

    RouteCollectorInterface::class => Group::create(),

    RouteCollectionInterface::class => static function (RouteCollectorInterface $collector) {
        $routes = new Routes();
        $userRoutes = new UserRoutes();
        $rbacRoutes = new RbacRoutes();

        $collector->addGroup(
            Group::create(
                null,
                array_merge($routes->getRoutes(), $userRoutes->getRoutes(), $rbacRoutes->getRoutes())
            )->addMiddleware(FormatDataResponse::class)
        );

        return new RouteCollection($collector);
    }
];
