<?php

declare(strict_types=1);

namespace Yii\Component;

use App\Service\Mailer;
use App\Service\Parameters;
use App\Module\User\Entity\User;
use App\Module\User\Entity\Token;
use App\Module\User\Form\Registration;
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
            Reference::to(Parameters::class),
            Reference::to(Aliases::class),
            Reference::to(ConnectionInterface::class),
            Reference::to(Mailer::class),
            Reference::to(Registration::class),
            Reference::to(Token::class),
            Reference::to(User::class),
            Reference::to(UrlGeneratorInterface::class)
        ]
    ],

    IdentityRepositoryInterface::class => UserRepository::class
];
