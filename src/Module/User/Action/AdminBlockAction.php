<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\Yii\Web\User\User;

final class AdminBlockAction
{
    public function block(
        User $identity,
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
        UserRepository $userRepository,
        UrlGeneratorInterface $url,
        UrlMatcherInterface $urlMatcher,
        View $view
    ): ResponseInterface {
        $id = $request->getAttribute('id');

        if ($id === null || $identity->getId() === $id) {
            $view->addFlash(
                'is-danger',
                $settings->getMessageHeader(),
                $id === null ? 'The requested page does not exist.' : 'You can not block your own account.'
            );

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('admin/index'));
        }

        $user = $userRepository->findUserById($id);

        if ($user->isBlocked()) {
            $userRepository->unblock($user);
        } else {
            $userRepository->block($user);
        }

        $view->addFlash(
            'is-success',
            $settings->getMessageHeader(),
            $user->isBlocked() ? 'User has been unblocked.' : 'User has been blocked.'
        );

        return $responseFactory
            ->createResponse(302)
            ->withHeader('Location', $url->generate('admin/index'));
    }
}
