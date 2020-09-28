<?php

declare(strict_types=1);

namespace Yii;

use App\Action\About;
use App\Action\ContactForm;
use App\Action\Index;
use App\Module\User\Action\Login;
use App\Module\User\Action\Logout;
use App\Module\User\Action\Register;
use Yiisoft\Router\Route;

final class Routes
{
    public function getRoutes(): array
    {
        return [
            Route::get('/', [Index::class, 'index'])->name('index'),
            Route::get('/about', [About::class, 'about'])->name('about'),
            Route::methods(['GET', 'POST'], '/contact', [ContactForm::class, 'contact'])->name('contact'),

            /** config routes module user */
            Route::methods(['GET', 'POST'], '/auth/login', [Login::class, 'login'])
            ->name('auth/login'),

            Route::get('/auth/logout', [Logout::class, 'logout'])->name('auth/logout'),

            Route::methods(['GET', 'POST'], '/registration/register', [Register::class, 'register'])
                ->name('registration/register')
        ];
    }
}
