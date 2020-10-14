<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use RuntimeException;
use App\Module\User\Entity\User;
use App\Module\User\Entity\Token;
use App\Module\User\Form\Reset as ResetForm;
use App\Module\User\Repository\ModuleSettings as ModuleSettingsRepository;
use App\Module\User\Repository\TokenRepository;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class Reset
{
    public function reset(
        IdentityRepositoryInterface $identityRepository,
        ServerRequestInterface $request,
        ResetForm $resetForm,
        DataResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
        TokenRepository $tokenRepository,
        UrlGeneratorInterface $url,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $id = $request->getAttribute('id');
        $code =  $request->getAttribute('code');
        $user = null;
        $token = null;

        if ($id !== null) {
            $user = $identityRepository->findIdentity($id);
        }

        if ($user !== null && $code !== null) {
            $token = $tokenRepository->findTokenByParams(
                (int) $user->getId(),
                $request->getAttribute('code'),
                Token::TYPE_RECOVERY
            );
        }

        if (
            $user === null ||
            !$token instanceof Token ||
            $token->isExpired(0, $settings->getTokenRecoverWithin())
        ) {
            $view->addflash(
                'is-danger',
                $settings->getMessageHeader(),
                'The requested page does not exist.'
            );

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('index'));
        }

        if (
            $method === 'POST'
            && $resetForm->load($body)
            && $resetForm->validate()
            && $user !== null
            && $token instanceof Token
            && !$token->isExpired(0, $settings->getTokenRecoverWithin())
        ) {
            $token->delete();

            /** @var User $user */
            $user->passwordHashUpdate($resetForm->getAttributeValue('password'));

            $view->addFlash(
                'is-success',
                $settings->getMessageHeader(),
                'Your password has been changed.'
            );

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('index'));
        }

        return $view
            ->viewPath('@user/resources/views')
            ->renderWithLayout(
                '/recovery/reset',
                [
                    'id' => $id,
                    'code' => $code,
                    'data' => $resetForm
                ]
            );
    }
}
