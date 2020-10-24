<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Form\ResendForm;
use App\Module\User\Service\ResendService;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\ViewService;
use App\Service\WebControllerService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class ResendAction
{
    public function resend(
        ResendForm $resendForm,
        ResendService $resendService,
        ServerRequestInterface $request,
        ModuleSettingsRepository $settings,
        UrlGeneratorInterface $url,
        UserRepository $userRepository,
        ViewService $view,
        WebControllerService $webController
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();

        if (
            $method === 'POST'
            && $resendForm->load($body)
            && $resendForm->validate()
            && $resendService->run($resendForm, $userRepository)
        ) {
            return $webController
                ->withFlash(
                    'is-warning',
                    $settings->getMessageHeader(),
                    'Please check your email to activate your username.'
                )
                ->redirectResponse('index');
        }

        return $view
            ->viewPath('@user/resources/views')
            ->renderWithLayout(
                '/registration/resend',
                [
                    'action' => $url->generate('registration/resend'),
                    'body' => $body,
                    'data' => $resendForm,
                    'settings' => $settings,
                    'url' => $url
                ]
            );
    }
}
