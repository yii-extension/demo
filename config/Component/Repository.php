<?php

declare(strict_types=1);

namespace Yii\Component;

use App\Service\Mailer;
use App\Service\Parameters;
use App\Module\User\Entity\User;
use App\Module\User\Entity\Token;
use App\Module\User\Form\Registration;
use App\Module\User\Repository\UserRepository;
use App\Module\User\Repository\UserRepositoryInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Router\UrlGeneratorInterface;

return [
    UserRepositoryInterface::class => [
        '__class' => UserRepository::class,
        '__construct()' => [
            Reference::to(Parameters::class),
            Reference::to(Aliases::class),
            Reference::to(Mailer::class),
            Reference::to(Registration::class),
            Reference::to(Token::class),
            Reference::to(User::class),
            Reference::to(UrlGeneratorInterface::class)
        ]
    ],

    IdentityRepositoryInterface::class => UserRepositoryInterface::class
];
