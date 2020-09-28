<?php

declare(strict_types=1);

namespace App\Module\User\Config;

use App\Module\User\Action\Login;
use App\Module\User\Action\Logout;
use App\Module\User\Action\Register;
use Yiisoft\Router\Route;

final class Routes
{
    public function getRoutes(): array
    {
        return [
            /** config routes module user */
            Route::methods(['GET', 'POST'], '/auth/login', [Login::class, 'login'])
            ->name('auth/login'),

            Route::get('/auth/logout', [Logout::class, 'logout'])->name('auth/logout'),

            Route::methods(['GET', 'POST'], '/registration/register', [Register::class, 'register'])
                ->name('registration/register')
        ];
    }
}
