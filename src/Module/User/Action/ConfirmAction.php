<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\ActiveRecord\TokenAR;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\TokenRepository;
use App\Module\User\Repository\UserRepository;
use App\Module\User\Service\LoginService;
use App\Service\WebControllerService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ConfirmAction
{
    public function confirm(
        LoginService $loginService,
        ServerRequestInterface $request,
        ModuleSettingsRepository $settings,
        TokenRepository $tokenRepository,
        UserRepository $userRepository,
        WebControllerService $webController
    ): ResponseInterface {
        $id = $request->getAttribute('id');
        $code = $request->getAttribute('code');
        $ip = $request->getServerParams()['REMOTE_ADDR'];

        if ($id === null || ($user = $userRepository->findUserById($id)) === null || $code === null) {
            return $webController->notFoundResponse();
        }

        /**
         * @var TokenAR $token
         * @var UserAR $user
         */
        $token = $tokenRepository->findTokenByParams(
            (int) $user->getId(),
            $code,
            TokenAR::TYPE_CONFIRMATION
        );

        if ($token === null || $token->isExpired($settings->getTokenConfirmWithin())) {
            return $webController->notFoundResponse();
        }

        if (
            $loginService->isLoginConfirm($user, $ip)
            && !$token->isExpired($settings->getTokenConfirmWithin())
        ) {
            $token->delete();

            $user->updateAttributes([
                'unconfirmed_email' => null,
                'confirmed_at' => time()
            ]);

            return $webController
                ->withFlash(
                    'is-success',
                    $settings->getMessageHeader(),
                    'Your user has been confirmed.'
                )
                ->redirectResponse('admin/index');
        }

        return $webController
            ->withFlash(
                'is-danger',
                $settings->getMessageHeader(),
                'Your username could not be confirmed.'
            )
            ->redirectResponse('admin/index');
    }
}
