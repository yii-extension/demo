<?php

declare(strict_types=1);

namespace Yii\Component;

use App\Service\MailerService;
use App\Module\User\ActiveRecord\ProfileAR;
use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\ActiveRecord\TokenAR;
use App\Module\User\Repository\UserRepository;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use Psr\Log\LoggerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Router\UrlGeneratorInterface;

return [
    UserRepository::class => [
        '__class' => UserRepository::class,
        '__construct()' => [
            Reference::to(Aliases::class),
            Reference::to(InitialAvatar::class),
            Reference::to(ConnectionInterface::class),
            Reference::to(MailerService::class),
            Reference::to(LoggerInterface::class),
            Reference::to(ProfileAR::class),
            Reference::to(TokenAR::class),
            Reference::to(UserAR::class),
            Reference::to(UrlGeneratorInterface::class)
        ]
    ],

    IdentityRepositoryInterface::class => UserRepository::class
];
