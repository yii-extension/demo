<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Form\Login as LoginForm;
use App\Module\User\Repository\ModuleSettings as ModuleSettingsRepository;
use App\Module\User\Service\Login as LoginService;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class Login
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
                ->withHeader('Location', $url->generate('index'));
        }

        return $view
            ->viewPath('@user/resources/views')
            ->renderWithLayout('auth/login', ['body' => $body, 'data' => $loginForm, 'settings' => $settings]);
    }
}
