<?php

declare(strict_types=1);

namespace App\Module\Rbac\Config;

use App\Module\Rbac\Action\ItemAction;
use App\Module\Rbac\Action\ItemCreateAction;
use App\Module\Rbac\Action\ItemDeleteAction;
use App\Module\Rbac\Action\ItemEditAction;
use App\Module\Rbac\Action\ItemsApiAction;
use Yiisoft\DataResponse\Middleware\FormatDataResponseAsJson;
use Yiisoft\Router\Route;

final class Routes
{
    public function getRoutes(): array
    {
        return [
            /** item actions */
            Route::methods(['GET', 'POST'], '/item/index', [ItemAction::class, 'index'])
            ->name('item/index'),
            Route::methods(['GET', 'POST'], '/item/create', [ItemCreateAction::class, 'create'])
            ->name('item/create'),
            Route::methods(['GET', 'POST'], '/item/edit[/{id}]', [ItemEditAction::class, 'edit'])
                ->name('item/edit'),
            Route::methods(['GET', 'POST'], '/item/delete[/{id}]', [ItemDeleteAction::class, 'delete'])
                ->name('item/delete'),

            /** items api actions */
            Route::get('/items', [ItemsApiAction::class, 'index'])
                ->addMiddleware(FormatDataResponseAsJson::class)
                ->name('items/index'),
        ];
    }
}
