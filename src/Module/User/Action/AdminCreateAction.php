<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Form\RegisterForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class AdminCreateAction
{
    public function create(
        RegisterForm $registerForm,
        ServerRequestInterface $request,
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
            && $registerForm->load($body)
            && $registerForm->validate()
            && $userRepository->create($registerForm)
        ) {
            if (
                $userRepository->sendMailer(
                    $url,
                    $settings->getSubjectWelcome(),
                    ['html' => 'welcome', 'text' => 'text/welcome']
                )
            ) {
                $view->addFlash(
                    'is-info',
                    'System Notification - Yii Demo User Module AR.',
                    'The account has been created.'
                );
            }

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('admin/index'));
        }

        return $view
            ->viewPath('@user/resources/views')
            ->renderWithLayout(
                '/admin/_form',
                ['action' => $url->generate('admin/create'), 'data' => $registerForm, 'title' => 'Create user.']
            );
    }
}
