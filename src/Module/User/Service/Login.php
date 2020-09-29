<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\Entity\User as UserEntity;
use App\Module\User\Form\Login as LoginForm;
use App\Module\User\Repository\UserRepository;
use Yiisoft\Yii\Web\User\User;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class Login
{
    private LoginForm $loginForm;
    private User $user;

    public function __construct(LoginForm $loginForm, User $user)
    {
        $this->loginForm = $loginForm;
        $this->user = $user;

    }

    public function isLogin(IdentityRepositoryInterface $identityRepository, string $ip): bool
    {
        $login = $this->loginForm->getAttributeValue('login');
        $password = $this->loginForm->getAttributeValue('password');

        /** @var UserRepository $identityRepository */
        $user = $identityRepository->findUserByUsernameOrEmail($login);

        if ($user === null) {
            $this->loginForm->addError('password', 'Unregistered user/Invalid password.');
        }

        if (
            $user
            && $identityRepository->validatePassword(
                $this->loginForm,
                $password,
                $user->getAttribute('password_hash')
            )
            && $this->validateConfirmed($user)
        ) {
            $this->updateAttributeLogin($user, $ip);

            /** @var IdentityInterface $user */
            $result = $this->user->login($user);
        } else {
            $this->loginForm->addError('password', 'Unregistered user/Invalid password.');
            $result = false;
        }

        return $result;
    }

    public function isLoginConfirm(IdentityRepositoryInterface $identityRepository, string $id, string $ip): bool
    {
        $user = $identityRepository->findIdentity($id);

        $this->updateAttributeLogin($user, $ip);

        return $this->user->login($user);
    }

    private function updateAttributeLogin(UserEntity $user, string $ip): void
    {
        $user->updateAttributes(['ip_last_login' => $ip, 'last_login_at' => time()]);
    }

    private function validateConfirmed(UserEntity $user): bool
    {
        $result = true;

        if ($user->getAttribute('confirmed_at') === null) {
            $this->loginForm->addError('password', 'Please check your email to activate your account.');
            $result = false;
        }

        return $result;
    }
}
