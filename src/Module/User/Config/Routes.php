<?php

declare(strict_types=1);

namespace App\Module\User\Config;

use App\Module\User\Action\Confirm;
use App\Module\User\Action\Login;
use App\Module\User\Action\Logout;
use App\Module\User\Action\Register;
use App\Module\User\Action\Resend;
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
