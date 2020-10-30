<?php

declare(strict_types=1);

use App\Middleware\Guest;
use App\Module\User\Action\AdminAction;
use App\Module\User\Action\AdminBlockAction;
use App\Module\User\Action\AdminCreateAction;
use App\Module\User\Action\AdminConfirmAction;
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
use Yiisoft\Auth\Middleware\Authentication;
use Yiisoft\DataResponse\Middleware\FormatDataResponseAsJson;
use Yiisoft\Router\Route;

return [
    /** auth actions */
    Route::methods(['GET', 'POST'], '/auth/login', [LoginAction::class, 'login'])
        ->addMiddleware(Guest::class)
        ->name('auth/login'),
    Route::get('/auth/logout', [LogoutAction::class, 'logout'])
        ->addMiddleware(Authentication::class)
        ->name('auth/logout'),

    /** admin actions */
    Route::methods(['GET', 'POST'], '/admin/index', [AdminAction::class, 'index'])
        ->addMiddleware(Authentication::class)
        ->name('admin/index'),
    Route::methods(['GET', 'POST'], '/admin/block[/{id}]', [AdminBlockAction::class, 'block'])
        ->addMiddleware(Authentication::class)
        ->name('admin/block'),
    Route::methods(['GET', 'POST'], '/admin/create', [AdminCreateAction::class, 'create'])
        ->addMiddleware(Authentication::class)
        ->name('admin/create'),
    Route::methods(['GET', 'POST'], '/admin/confirm[/{id}]', [AdminConfirmAction::class, 'confirm'])
        ->addMiddleware(Authentication::class)
        ->name('admin/confirm'),
    Route::methods(['GET', 'POST'], '/admin/delete[/{id}]', [AdminDeleteAction::class, 'delete'])
        ->name('admin/delete')->addMiddleware(Authentication::class),
    Route::methods(['GET', 'POST'], '/admin/edit[/{id}]', [AdminEditAction::class, 'edit'])
        ->addMiddleware(Authentication::class)
        ->name('admin/edit'),
    Route::methods(['GET', 'POST'], '/admin/info[/{id}]', [AdminInfoAction::class, 'info'])
        ->addMiddleware(Authentication::class)
        ->name('admin/info'),
    Route::methods(['GET', 'POST'], '/admin/reset[/{id}]', [AdminResetPasswordAction::class, 'reset'])
        ->addMiddleware(Authentication::class)
        ->name('admin/reset'),

    /** api users */
    Route::get('/users', [UserApiAction::class, 'index'])
        ->addMiddleware(FormatDataResponseAsJson::class)
        ->name('users/index'),

    /** recovery actions */
    Route::methods(['GET', 'POST'], '/recovery/request', [RequestAction::class, 'request'])
        ->addMiddleware(Guest::class)
        ->name('recovery/request'),

    Route::methods(['GET', 'POST'], '/recovery/reset[/{id}/{code}]', [ResetAction::class, 'reset'])
        ->addMiddleware(Guest::class)
        ->name('recovery/reset'),

    /** registration actions */
    Route::get('/registration/confirm[/{id}/{code}]', [ConfirmAction::class, 'confirm'])
        ->addMiddleware(Guest::class)
        ->name('registration/confirm'),

    Route::methods(['GET', 'POST'], '/registration/register', [RegisterAction::class, 'register'])
        ->addMiddleware(Guest::class)
        ->name('registration/register'),

    Route::methods(['GET', 'POST'], '/registration/resend', [ResendAction::class, 'resend'])
        ->addMiddleware(Guest::class)
        ->name('registration/resend')
];
