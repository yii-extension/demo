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

final class AdminEditAction
{
    public function edit(
        ModuleSettingsRepository $settings,
        RegisterForm $registerForm,
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        UrlGeneratorInterface $url,
        UserRepository $userRepository,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $id = $request->getAttribute('id');

        $userRepository->loadData($registerForm, $id);

        if (
            $method === 'POST'
            && $registerForm->load($body)
            && $registerForm->validate()
            && $userRepository->update($registerForm, $id)
        ) {
            if (
                $userRepository->sendMailer(
                    $url,
                    $settings->getSubjectPassword(),
                    ['html' => 'newpassword', 'text' => 'text/newpassword']
                )
            ) {
                $view->addFlash(
                    'is-info',
                    'System Notification - Yii Demo User Module AR.',
                    'The account has been updated.'
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
                [
                    'action' => $url->generate('admin/edit', ['id' => $id]),
                    'data' => $registerForm,
                    'title' => 'Edit User.'
                ],
                ['id' => $id]
            );
    }
}
