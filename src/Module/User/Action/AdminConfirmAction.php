<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\WebControllerService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AdminConfirmAction
{
    public function confirm(
        ServerRequestInterface $request,
        ModuleSettingsRepository $settings,
        UserRepository $userRepository,
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
