<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Service\View;
use App\Module\User\Form\RegisterForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Module\User\Service\ResendService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\Web\User\User;

final class AdminResetPasswordAction
{
    public function reset(
        User $identity,
        ModuleSettingsRepository $settings,
        RegisterForm $registerForm,
        ServerRequestInterface $request,
        ResendService $resendService,
        DataResponseFactoryInterface $responseFactory,
        UrlGeneratorInterface $url,
        UserRepository $userRepository,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $id = $request->getAttribute('id');

        if ($identity->getId() === $id) {
            $view->addFlash(
                'is-danger',
                'System Notification - Yii Demo User Module AR.',
                'You cannot resend the password your own user.'
            );

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('admin/index'));
        }

        if ($userRepository->resetPassword($id)) {
            $userRepository->sendMailer(
                $url,
                $settings->getSubjectPassword(),
                ['html' => 'newpassword', 'text' => 'text/newpassword']
            );

            $view->addFlash(
                'is-success',
                'System Notification - Yii Demo User Module AR.',
                'The password has been changed.'
            );
        }

        return $responseFactory
            ->createResponse(302)
            ->withHeader('Location', $url->generate('admin/index'));
    }
}
