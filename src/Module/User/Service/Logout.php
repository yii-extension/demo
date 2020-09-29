<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\Entity\User as UserEntity;
use Yiisoft\Yii\Web\User\User;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class Logout
{
    private IdentityRepositoryInterface $identityRepository;

    public function __construct(IdentityRepositoryInterface $identityRepository)
    {
        $this->identityRepository = $identityRepository;
    }

    public function run(User $user): bool
    {
        $identity = $this->identityRepository->findIdentity($user->getId());

        /** @var UserEntity $identity */
        $identity->updateAttributes(['last_logout_at' => time()]);

        return $user->logout();
    }
}
