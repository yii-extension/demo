<?php

declare(strict_types=1);

namespace App\Module\User\Config;

use App\Module\User\Action\AdminAction;
use App\Module\User\Action\AdminCreateAction;
use App\Module\User\Action\AdminDeleteAction;
use App\Module\User\Action\AdminEditAction;
use App\Module\User\Action\AdminInfoAction;
use App\Module\User\Action\AdminResetPasswordAction;
use App\Module\User\Action\ConfirmAction;
use App\Module\User\Action\LoginAction;
use App\Module\User\Action\LogoutAction;
use App\Module\User\Action\RequestAction;
use App\Module\User\Action\RegisterAction;
use App\Module\User\Action\ResendAction;
use App\Module\User\Action\ResetAction;
use App\Module\User\Action\UserApiAction;
use Yiisoft\DataResponse\Middleware\FormatDataResponseAsJson;
use Yiisoft\Router\Route;

final class Routes
{
    public function getRoutes(): array
    {
        return [
            /** auth actions */
            Route::methods(['GET', 'POST'], '/auth/login', [LoginAction::class, 'login'])
            ->name('auth/login'),
            Route::get('/auth/logout', [LogoutAction::class, 'logout'])->name('auth/logout'),

            /** admin actions */
            Route::methods(['GET', 'POST'], '/admin/index', [AdminAction::class, 'index'])
                ->name('admin/index'),
            Route::methods(['GET', 'POST'], '/admin/create', [AdminCreateAction::class, 'create'])
                ->name('admin/create'),
            Route::methods(['GET', 'POST'], '/admin/delete[/{id}]', [AdminDeleteAction::class, 'delete'])
                ->name('admin/delete'),
            Route::methods(['GET', 'POST'], '/admin/edit[/{id}]', [AdminEditAction::class, 'edit'])
                ->name('admin/edit'),
            Route::methods(['GET', 'POST'], '/admin/info[/{id}]', [AdminInfoAction::class, 'info'])
                ->name('admin/info'),
            Route::methods(['GET', 'POST'], '/admin/reset[/{id}]', [AdminResetPasswordAction::class, 'reset'])
                ->name('admin/reset'),

            /** api users */
            Route::get('/users', [UserApiAction::class, 'index'])
                ->addMiddleware(FormatDataResponseAsJson::class)
                ->name('users/index'),

            /** recovery actions */
            Route::methods(['GET', 'POST'], '/recovery/request', [RequestAction::class, 'request'])
                ->name('recovery/request'),

            Route::methods(['GET', 'POST'], '/recovery/reset[/{id}/{code}]', [ResetAction::class, 'reset'])
                ->name('recovery/reset'),

            /** registration actions */
            Route::get('/registration/confirm[/{id}/{code}]', [ConfirmAction::class, 'confirm'])
                ->name('registration/confirm'),

            Route::methods(['GET', 'POST'], '/registration/register', [RegisterAction::class, 'register'])
                ->name('registration/register'),

            Route::methods(['GET', 'POST'], '/registration/resend', [ResendAction::class, 'resend'])
                ->name('registration/resend')
        ];
    }
}
