<?php

declare(strict_types=1);

namespace Yii\Component;

use Yii\Params;
use Yiisoft\Auth\AuthenticationMethodInterface;
use Yiisoft\Yii\Web\User\UserAuth;

$params = new Params();

return [
    /** component auth */
    UserAuth::class => [
        '__class' => UserAuth::class,
        'withAuthUrl()' => [$params->getLoginUrl()]
    ],

    AuthenticationMethodInterface::class => UserAuth::class,
];
