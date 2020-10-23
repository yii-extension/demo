<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\ActiveRecord\TokenAR;
use App\Module\User\Form\ResetForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\TokenRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\View;
use App\Service\WebControllerService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class ResetAction
{
    public function reset(
        ServerRequestInterface $request,
        ResetForm $resetForm,
        DataResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
        TokenRepository $tokenRepository,
        UrlGeneratorInterface $url,
        UserRepository $userRepository,
        View $view,
        WebControllerService $webController
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $id = $request->getAttribute('id');
        $code =  $request->getAttribute('code');
        $user = null;
        $token = null;

        if ($id === null || ($user = $userRepository->findUserById($id)) === null || $code === null) {
            return $webController->notFoundResponse();
        }

        $token = $tokenRepository->findTokenByParams(
            (int) $user->getId(),
            $request->getAttribute('code'),
            TokenAR::TYPE_RECOVERY
        );

        if ($token === null || $token->isExpired(0, $settings->getTokenRecoverWithin())) {
            return $webController
                ->withFlash(
                    'is-danger',
                    $settings->getMessageHeader(),
                    'The requested page does not exist.'
                )
                ->redirectResponse('index');
        }

        if (
            $method === 'POST'
            && $resetForm->load($body)
            && $resetForm->validate()
            && !$token->isExpired(0, $settings->getTokenRecoverWithin())
        ) {
            $token->delete();

            /** @var UserAR $user */
            $user->passwordHashUpdate($resetForm->getAttributeValue('password'));

            return $webController
                ->withFlash(
                    'is-success',
                    $settings->getMessageHeader(),
                    'Your password has been changed.'
                )
                ->redirectResponse('index');
        }

        return $view
            ->viewPath('@user/resources/views')
            ->renderWithLayout(
                '/recovery/reset',
                [
                    'action' => $url->generate('recovery/reset', ['id' => $id, 'code' => $code]),
                    'body' => $body,
                    'data' => $resetForm
                ]
            );
    }
}
