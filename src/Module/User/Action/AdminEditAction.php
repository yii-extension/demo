<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Form\RegisterForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\View;
use App\Service\WebControllerService;
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
        View $view,
        WebControllerService $webController
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $id = $request->getAttribute('id');
        $registerForm->ip($request->getServerParams()['REMOTE_ADDR']);

        if ($id === null || ($user = $userRepository->findUserById($id)) === null) {
            return $webController->notFoundResponse();
        }

        $userRepository->loadData($user, $registerForm);

        if (
            $method === 'POST'
            && $registerForm->load($body)
            && $registerForm->validate()
            && $userRepository->update($user, $registerForm)
        ) {
            if (
                $userRepository->sendMailer(
                    $url,
                    $settings->getSubjectPassword(),
                    ['html' => 'newpassword', 'text' => 'text/newpassword'],
                    false,
                    true,
                    $user
                )
            ) {
                return $webController
                ->withFlash(
                    'is-info',
                    $settings->getMessageHeader(),
                    'The account has been updated.'
                )
                ->redirectResponse('admin/index');
            }
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
