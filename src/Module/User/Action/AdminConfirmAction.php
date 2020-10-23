<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\View;
use App\Service\WebControllerService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;

final class AdminConfirmAction
{
    public function confirm(
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
        UserRepository $userRepository,
        UrlGeneratorInterface $url,
        UrlMatcherInterface $urlMatcher,
        View $view,
        WebControllerService $webController
    ): ResponseInterface {
        $id = $request->getAttribute('id');

        if ($id !== null && ($user = $userRepository->findUserById($id)) !== null) {
            $userRepository->confirm($user);

            return $webController
                ->withFlash(
                    'is-success',
                    $settings->getMessageHeader(),
                    'Your user has been confirmed.'
                )
                ->redirectResponse('admin/index');
        }

        return $webController->notFoundResponse();
    }
}
