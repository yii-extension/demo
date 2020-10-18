<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Service\LogoutService;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\Web\User\User;

final class LogoutAction
{
    public function logout(
        DataResponseFactoryInterface $responseFactory,
        LogoutService $logoutService,
        UrlGeneratorInterface $url,
        User $userIdentity
    ): ResponseInterface {
        $logoutService->run($userIdentity);

        return $responseFactory
            ->createResponse(302)
            ->withHeader('Location', $url->generate('index'));
    }
}
