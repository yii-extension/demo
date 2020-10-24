<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Repository\UserRepository;
use App\Service\ViewService;
use App\Service\WebControllerService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;

final class AdminInfoAction
{
    public function info(
        ServerRequestInterface $request,
        UserRepository $userRepository,
        UrlGeneratorInterface $url,
        UrlMatcherInterface $urlMatcher,
        ViewService $view,
        WebControllerService $webController
    ): ResponseInterface {
        $id = $request->getAttribute('id');

        if ($id === null || ($user = $userRepository->findUserById($id)) === null) {
            return $webController->notFoundResponse();
        }

        return $view
            ->viewPath('@user/resources/views')
            ->renderWithLayout(
                '/admin/_info',
                ['data' => $user, 'title' => 'Info user', 'url' => $url, 'urlMatcher' => $urlMatcher],
                ['id' => $user->getAttribute('id')]
            );
    }
}
