<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Entity\User;
use App\Module\User\Entity\Token;
use App\Module\User\Repository\ModuleSettings as ModuleSettingsRepository;
use App\Module\User\Repository\TokenRepository;
use App\Module\User\Service\Login as LoginService;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class Confirm
{
    public function confirm(
        IdentityRepositoryInterface $identityRepository,
        LoginService $loginService,
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
        TokenRepository $tokenRepository,
        UrlGeneratorInterface $url,
        View $view
    ): ResponseInterface {
        $id = (string) $request->getAttribute('id');
        $code = $request->getAttribute('code');
        $ip = $request->getServerParams()['REMOTE_ADDR'];
        $user = null;
        $token = null;

        if ($id !== null) {
            $user = $identityRepository->findIdentity($id);
        }

        if ($user !== null && $code !== null) {
            $token = $tokenRepository->findTokenByParams(
                (int) $user->getId(),
                $code,
                Token::TYPE_CONFIRMATION
            );
        }

        if (
            $user === null ||
            !$token instanceof Token ||
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
            $user !== null
            && $token instanceof Token
            && $loginService->isLoginConfirm($identityRepository, $id, $ip)
            && !$token->isExpired($settings->getTokenConfirmWithin())
        ) {
            $token->delete();

            /** @var User $user */
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
            ->withHeader('Location', $url->generate('index'));
    }
}
