<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Form\ResendForm;
use App\Module\User\Service\ResendService;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class ResendAction
{
    public function resend(
        IdentityRepositoryInterface $identityRepository,
        ResendForm $resendForm,
        ResendService $resendService,
        DataResponseFactoryInterface $responseFactory,
        ServerRequestInterface $request,
        ModuleSettingsRepository $settings,
        UrlGeneratorInterface $url,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();

        if (
            $method === 'POST'
            && $resendForm->load($body)
            && $resendForm->validate()
            && $resendService->run($resendForm, $identityRepository)
        ) {
            $view->addFlash(
                'is-warning',
                $settings->getMessageHeader(),
                'Please check your email to activate your username.'
            );

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('index'));
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