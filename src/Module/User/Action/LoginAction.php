<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Form\LoginForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Service\LoginService;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class LoginAction
{
    public function login(
        IdentityRepositoryInterface $identityRepository,
        LoginForm $loginForm,
        LoginService $loginService,
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
        UrlGeneratorInterface $url,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $ip = $request->getServerParams()['REMOTE_ADDR'];

        if (
            $method === 'POST'
            && $loginForm->load($body)
            && $loginForm->validate()
            && $loginService->isLogin($identityRepository, $ip)
        ) {
            $view->addFlash(
                'is-success',
                $settings->getMessageHeader(),
                'Sign in successful - ' . date("F j, Y, g:i a")
            );

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('admin/index'));
        }

        return $view
            ->viewPath('@user/resources/views')
            ->renderWithLayout(
                'auth/login',
                [
                    'action' => $url->generate('auth/login'),
                    'body' => $body,
                    'data' => $loginForm,
                    'settings' => $settings,
                    'url' => $url
                ]
            );
    }
}
