<?php

declare(strict_types=1);

namespace Yii\Component;

use Yiisoft\Auth\AuthenticationMethodInterface;
use Yiisoft\Yii\Web\User\UserAuth;

return [
    /** component auth */
    UserAuth::class => [
        '__class' => UserAuth::class,
        'withAuthUrl()' => [$params['loginUrl']]
    ],

    AuthenticationMethodInterface::class => UserAuth::class,
];
