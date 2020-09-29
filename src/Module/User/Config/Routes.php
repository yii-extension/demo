<?php

declare(strict_types=1);

namespace App\Module\User\Config;

use App\Module\User\Action\Confirm;
use App\Module\User\Action\Login;
use App\Module\User\Action\Logout;
use App\Module\User\Action\Request;
use App\Module\User\Action\Register;
use App\Module\User\Action\Resend;
use App\Module\User\Action\Reset;
use Yiisoft\Router\Route;

final class Routes
{
    public function getRoutes(): array
    {
        return [
            /** auth actions */
            Route::methods(['GET', 'POST'], '/auth/login', [Login::class, 'login'])
            ->name('auth/login'),

            Route::get('/auth/logout', [Logout::class, 'logout'])->name('auth/logout'),

            /** recovery actions */
            Route::methods(['GET', 'POST'], '/recovery/request', [Request::class, 'request'])
                ->name('recovery/request'),

            Route::methods(['GET', 'POST'], '/recovery/reset[/{id}/{code}]', [Reset::class, 'reset'])
                ->name('recovery/reset'),

            /** registration actions */
            Route::get('/registration/confirm[/{id}/{code}]', [Confirm::class, 'confirm'])
                ->name('registration/confirm'),

            Route::methods(['GET', 'POST'], '/registration/register', [Register::class, 'register'])
                ->name('registration/register'),

            Route::methods(['GET', 'POST'], '/registration/resend', [Resend::class, 'resend'])
                ->name('registration/resend')
        ];
    }
}
