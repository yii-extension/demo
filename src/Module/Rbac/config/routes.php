<?php

declare(strict_types=1);

use App\Module\Rbac\Action\ItemAction;
use App\Module\Rbac\Action\ItemCreateAction;
use App\Module\Rbac\Action\ItemDeleteAction;
use App\Module\Rbac\Action\ItemEditAction;
use App\Module\Rbac\Action\ItemsApiAction;
use Yiisoft\DataResponse\Middleware\FormatDataResponseAsJson;
use Yiisoft\Auth\Middleware\Authentication;
use Yiisoft\Router\Route;

return [
    /** item actions */
    Route::methods(['GET', 'POST'], '/item/index', [ItemAction::class, 'index'])
        ->addMiddleware(Authentication::class)
        ->name('item/index'),
    Route::methods(['GET', 'POST'], '/item/create', [ItemCreateAction::class, 'create'])
        ->addMiddleware(Authentication::class)
        ->name('item/create'),
    Route::methods(['GET', 'POST'], '/item/edit[/{id}]', [ItemEditAction::class, 'edit'])
        ->addMiddleware(Authentication::class)
        ->name('item/edit'),
    Route::methods(['GET', 'POST'], '/item/delete[/{id}]', [ItemDeleteAction::class, 'delete'])
        ->addMiddleware(Authentication::class)
        ->name('item/delete'),

    /** items api actions */
    Route::get('/items', [ItemsApiAction::class, 'index'])
        ->addMiddleware(Authentication::class)
        ->addMiddleware(FormatDataResponseAsJson::class)
        ->name('items/index')
];
