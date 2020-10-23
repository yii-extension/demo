<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\ActiveRecord\UserAr;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\Web\User\User;

final class AdminDeleteAction
{
    public function delete(
        User $identity,
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
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
                $settings->getMessageHeader(),
                'You cannot delete your own user.'
            );

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('admin/index'));
        }

        $user = $userRepository->findUserById($id);

        $user->delete();

        $view->addFlash(
            'is-danger',
            $settings->getMessageHeader(),
            'The data has been delete.'
        );

        return $responseFactory
            ->createResponse(302)
            ->withHeader('Location', $url->generate('admin/index'));
    }
}
