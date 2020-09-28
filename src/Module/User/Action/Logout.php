<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Service\Login;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\Web\User\User;

final class Logout
{
    public function logout(
        DataResponseFactoryInterface $responseFactory,
        Login $login,
        UrlGeneratorInterface $url,
        User $userIdentity
    ): ResponseInterface {
        $login->logout($userIdentity);

        return $responseFactory
            ->createResponse(302)
            ->withHeader('Location', $url->generate('index'));
    }
}
