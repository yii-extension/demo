<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\WebControllerService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Yii\Web\User\User;

final class AdminDeleteAction
{
    public function delete(
        User $identity,
        ServerRequestInterface $request,
        ModuleSettingsRepository $settings,
        UserRepository $userRepository,
        WebControllerService $webController
    ): ResponseInterface {
        $id = $request->getAttribute('id');

        if ($id !== null && $identity->getId() !== $id && ($user = $userRepository->findUserById($id)) !== null) {
            $user->delete();

            return $webController
                ->withFlash(
                    'is-danger',
                    $settings->getMessageHeader(),
                    'The data has been delete.'
                )
                ->redirectResponse('admin/index');
        }

        return $webController
            ->notFoundResponse(
                $identity->getId() === $id ? 'You cannot delete your own user.' : null
            );
    }
}
