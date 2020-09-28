<?php

declare(strict_types=1);

namespace App\Module\User\Repository;

use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\Auth\IdentityRepositoryInterface;

interface UserRepositoryInterface extends IdentityRepositoryInterface
{
    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?ActiveRecord;
}
