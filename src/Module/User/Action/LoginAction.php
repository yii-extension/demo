<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Form\LoginForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Module\User\Service\LoginService;
use App\Service\ViewService;
use App\Service\WebControllerService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class LoginAction
{
    public function login(
        LoginForm $loginForm,
        LoginService $loginService,
        ServerRequestInterface $request,
        ModuleSettingsRepository $settings,
        UrlGeneratorInterface $url,
        UserRepository $userRepository,
        ViewService $view,
        WebControllerService $webController
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $ip = $request->getServerParams()['REMOTE_ADDR'];

        if (
            $method === 'POST'
            && $loginForm->load($body)
            && $loginForm->validate()
            && $loginService->isLogin($userRepository, $ip)
        ) {
            return $webController
                ->withFlash(
                    'is-success',
                    $settings->getMessageHeader(),
                    'Sign in successful - ' . date("F j, Y, g:i a")
                )
                ->redirectResponse('admin/index');
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
