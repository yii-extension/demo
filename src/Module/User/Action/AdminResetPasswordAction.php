<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\ActiveRecord\UserAR;
use App\Service\WebControllerService;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\Web\User\User;

final class AdminResetPasswordAction
{
    public function reset(
        User $identity,
        ServerRequestInterface $request,
        ModuleSettingsRepository $settings,
        UrlGeneratorInterface $url,
        UserRepository $userRepository,
        WebControllerService $webController
    ): ResponseInterface {
        $id = $request->getAttribute('id');

        /** @var UserAR $user */
        if (
            $id !== null &&
            $identity->getId() !== $id &&
            ($user = $userRepository->findUserById($id)) !== null &&
            $userRepository->resetPassword($user)) {
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
            ->notFoundResponse(
                $identity->getId() === $id ? 'You cannot resend the password your own user.' : null
            );
    }
}
