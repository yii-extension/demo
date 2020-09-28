<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\Entity\User as UserEntity;
use App\Module\User\Form\Login as LoginForm;
use App\Module\User\Repository\UserRepositoryInterface;
use Yiisoft\Yii\Web\User\User;

final class Login
{
    private LoginForm $loginForm;
    private User $user;
    private UserRepositoryInterface $userRepository;

    public function __construct(LoginForm $loginForm, User $user, UserRepositoryInterface $userRepository)
    {
        $this->loginForm = $loginForm;
        $this->user = $user;
        $this->userRepository = $userRepository;
    }

    public function isLogin(string $ip): bool
    {
        $login = $this->loginForm->getAttributeValue('login');
        $password = $this->loginForm->getAttributeValue('password');

        /** @var UserEntity $user */
        $user = $this->userRepository->findUserByUsernameOrEmail($login);

        if ($user === null) {
            $this->loginForm->addError('password', 'Unregistered user/Invalid password.');
        }

        if (
            $user
            && $this->userRepository->validatePassword(
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

    public function logout(User $user): bool
    {
        $identity = $this->userRepository->findIdentity($user->getId());

        $identity->updateAttributes(['last_logout_at' => time()]);

        return $user->logout();
    }

    public function isLoginConfirm(string $id, string $ip): bool
    {
        $user = $this->userRepository->findIdentity($id);

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
