<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\ActiveRecord\UserAr;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\View;
use App\Service\WebControllerService;
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
        View $view,
        WebControllerService $webController
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $id = $request->getAttribute('id');

        if ($id === null || $identity->getId() === $id || ($user = $userRepository->findUserById($id)) === null) {
            return $webController
                ->notFoundResponse(
                    $identity->getId() === $id ? 'You cannot delete your own user.' : null
                );
        }

        $user = $userRepository->findUserById($id);

        $user->delete();

        return $webController
            ->withFlash(
                'is-danger',
                $settings->getMessageHeader(),
                'The data has been delete.'
            )
            ->redirectResponse('admin/index');
    }
}
