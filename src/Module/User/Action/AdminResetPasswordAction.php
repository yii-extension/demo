<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Service\View;
use App\Service\WebControllerService;
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
        RegisterForm $registerForm,
        ServerRequestInterface $request,
        ResendService $resendService,
        DataResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
        UrlGeneratorInterface $url,
        UserRepository $userRepository,
        View $view,
        WebControllerService $webController
    ): ResponseInterface {
        $id = $request->getAttribute('id');

        if ($id === null || $identity->getId() === $id || ($user = $userRepository->findUserById($id)) === null) {
            return $webController
                ->notFoundResponse(
                    $identity->getId() === $id ? 'You cannot resend the password your own user.' : null
                );
        }

        if ($userRepository->resetPassword($user)) {
            $userRepository->sendMailer(
                $url,
                $settings->getSubjectPassword(),
                ['html' => 'newpassword', 'text' => 'text/newpassword'],
                false,
                true,
                $user
            );

            return $webController
                ->withFlash(
                    'is-success',
                    $settings->getMessageHeader(),
                    'The password has been changed.'
                )
                ->redirectResponse('admin/index');
        }

        return $webController
            ->withFlash(
                'is-danger',
                $settings->getMessageHeader(),
                'The password could not be changed.'
            )
            ->redirectResponse('admin/index');
    }
}
