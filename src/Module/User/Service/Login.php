<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\Entity\User as UserEntity;
use App\Module\User\Form\Login as LoginForm;
use Yiisoft\Yii\Web\User\User;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class Login
{
    private LoginForm $loginForm;
    private User $user;
    private IdentityRepositoryInterface $identityRepository;

    public function __construct(IdentityRepositoryInterface $identityRepository, LoginForm $loginForm, User $user)
    {
        $this->identityRepository = $identityRepository;
        $this->loginForm = $loginForm;
        $this->user = $user;

    }

    public function isLogin(string $ip): bool
    {
        $login = $this->loginForm->getAttributeValue('login');
        $password = $this->loginForm->getAttributeValue('password');

        /**
         * @psalm-suppress UndefinedInterfaceMethod
         * @var UserEntity $user
         */
        $user = $this->identityRepository->findUserByUsernameOrEmail($login);

        if ($user === null) {
            $this->loginForm->addError('password', 'Unregistered user/Invalid password.');
        }

        /** @psalm-suppress UndefinedInterfaceMethod */
        if (
            $user
            && $this->identityRepository->validatePassword(
                $this->loginForm,
                $password,
                $user->getAttribute('password_hash')
            )
            && $this->validateConfirmed($user)
        ) {
            $this->updateAttributeLogin($user, $ip);
            $result = $this->user->login($user);
        } else {
            $this->loginForm->addError('password', 'Unregistered user/Invalid password.');
            $result = false;
        }

        return $result;
    }

    public function isLoginConfirm(string $id, string $ip): bool
    {
        $user = $this->identityRepository->findIdentity($id);

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
