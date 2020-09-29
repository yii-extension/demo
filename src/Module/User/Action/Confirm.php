<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Entity\User;
use App\Module\User\Entity\Token;
use App\Module\User\Repository\TokenRepository;
use App\Module\User\Service\Login as LoginService;
use App\Service\Parameters;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class Confirm
{
    public function confirm(
        Parameters $app,
        IdentityRepositoryInterface $identityRepository,
        LoginService $loginService,
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
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
            $token->isExpired($app->get('user.tokenConfirmWithin'))
        ) {
            $view->addFlash(
                'is-danger',
                $app->get('user.messageHeader'),
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
            && !$token->isExpired($app->get('user.tokenConfirmWithin'))
        ) {
            $token->delete();

            /** @var User $user */
            $user->updateAttributes([
                'unconfirmed_email' => null,
                'confirmed_at' => time()
            ]);

            $view->addFlash(
                'is-success',
                $app->get('user.messageHeader'),
                'Your user has been confirmed.'
            );
        }

        return $responseFactory
            ->createResponse(302)
            ->withHeader('Location', $url->generate('index'));
    }
}
