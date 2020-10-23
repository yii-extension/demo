<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\ActiveRecord\TokenAR;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\TokenRepository;
use App\Module\User\Repository\UserRepository;
use App\Module\User\Service\LoginService;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\Web\User\User;

final class ConfirmAction
{
    public function confirm(
        LoginService $loginService,
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
        TokenRepository $tokenRepository,
        UrlGeneratorInterface $url,
        User $identity,
        UserRepository $userRepository,
        View $view
    ): ResponseInterface {
        $id = $request->getAttribute('id');
        $code = $request->getAttribute('code');
        $ip = $request->getServerParams()['REMOTE_ADDR'];
        $user = null;
        $token = null;

        if ($id !== null) {
            $user = $userRepository->findUserById($id);
        }

        if ($user !== null && $code !== null) {
            $token = $tokenRepository->findTokenByParams(
                (int) $user->getId(),
                $code,
                TokenAR::TYPE_CONFIRMATION
            );
        }

        if (
            $user === null ||
            !$token instanceof TokenAR ||
            $token->isExpired($settings->getTokenConfirmWithin())
        ) {
            $view->addFlash(
                'is-danger',
                $settings->getMessageHeader(),
                'The requested page does not exist.'
            );

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('index'));
        }

        if (
            $loginService->isLoginConfirm($userRepository, $id, $ip)
            && !$token->isExpired($settings->getTokenConfirmWithin())
        ) {
            $token->delete();

            /** @var UserAR $user */
            $user->updateAttributes([
                'unconfirmed_email' => null,
                'confirmed_at' => time()
            ]);

            $view->addFlash(
                'is-success',
                $settings->getMessageHeader(),
                'Your user has been confirmed.'
            );
        }

        return $responseFactory
            ->createResponse(302)
            ->withHeader(
                'Location',
                $identity->getId() === null ? $url->generate('index') : $url->generate('admin/index')
            );
    }
}
