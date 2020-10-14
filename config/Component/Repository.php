<?php

declare(strict_types=1);

namespace Yii\Component;

use App\Service\Mailer;
use App\Module\User\Entity\User;
use App\Module\User\Entity\Token;
use App\Module\User\Form\Register;
use App\Module\User\Repository\ModuleSettings;
use App\Module\User\Repository\UserRepository;
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
            Reference::to(ConnectionInterface::class),
            Reference::to(Mailer::class),
            Reference::to(Register::class),
            Reference::to(ModuleSettings::class),
            Reference::to(Token::class),
            Reference::to(User::class),
            Reference::to(UrlGeneratorInterface::class)
        ]
    ],

    IdentityRepositoryInterface::class => UserRepository::class
];
