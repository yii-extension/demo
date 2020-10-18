<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\ActiveRecord\UserAR;
use App\Service\View;
use App\Module\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;

final class AdminInfoAction
{
    public function info(
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        UserRepository $userRepository,
        UrlGeneratorInterface $url,
        UrlMatcherInterface $urlMatcher,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $id = $request->getAttribute('id');

        /** @var UserAR $user */
        $user = $userRepository->findIdentity($id);

        return $view
            ->viewPath('@user/resources/views')
            ->renderWithLayout(
                '/admin/_info',
                ['data' => $user, 'title' => 'Info user', 'url' => $url, 'urlMatcher' => $urlMatcher],
                ['id' => $user->getAttribute('id')]
            );
    }
}
