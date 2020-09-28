<?php

declare(strict_types=1);

namespace Yii\Component;

use Yiisoft\Csrf\TokenStorage\CsrfTokenStorageInterface;
use Yiisoft\Csrf\TokenStorage\SessionCsrfTokenStorage;
use Yiisoft\Session\Session as YiiSession;
use Yiisoft\Session\SessionInterface;

return [
    /** component session */
    SessionInterface::class => [
        '__class' => YiiSession::class,
        '__construct()' => [
            ['cookie_secure' => 0],
            null
        ]
    ],

    CsrfTokenStorageInterface::class => SessionCsrfTokenStorage::class,
];
