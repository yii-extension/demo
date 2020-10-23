<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Form\RequestForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Module\User\Service\RequestService;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class RequestAction
{
    public function request(
        RequestForm $requestForm,
        ServerRequestInterface $request,
        RequestService $requestService,
        DataResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
        UrlGeneratorInterface $url,
        UserRepository $userRepository,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();

        if (
            $method === 'POST'
            && $requestForm->load($body)
            && $requestForm->validate()
            && $requestService->run($requestForm, $userRepository)
        ) {
            $view->addFlash(
                'is-info',
                $settings->getMessageHeader(),
                'Please check your email to change your password.'
            );

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('index'));
        }

        if ($settings->isPasswordRecovery()) {
            return $view
                ->viewPath('@user/resources/views')
                ->renderWithLayout(
                    '/recovery/request',
                    [
                        'action' => $url->generate('recovery/request'),
                        'body' => $body,
                        'data' => $requestForm,
                        'url' => $url
                    ]
                );
        }

        $view->addFlash(
            'is-danger',
            $settings->getMessageHeader(),
            'Module password recovery user is disabled in the application configuration.'
        );

        return $responseFactory
            ->createResponse(302)
            ->withHeader('Location', $url->generate('index'));
    }
}
