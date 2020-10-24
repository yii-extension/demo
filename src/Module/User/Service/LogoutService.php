<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\Repository\UserRepository;
use Yiisoft\Yii\Web\User\User;

final class LogoutService
{
    public function run(UserRepository $userRepository, User $identity): bool
    {
        /** @var UserAR $user */
        $user = $userRepository->findUserById($identity->getId());

        $user->updateAttributes(['last_logout_at' => time()]);

        return $identity->logout();
    }
}
