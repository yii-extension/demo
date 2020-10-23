<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\Form\LoginForm;
use App\Module\User\Repository\UserRepository;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Yii\Web\User\User;

final class LoginService
{
    private LoginForm $loginForm;
    private User $user;

    public function __construct(LoginForm $loginForm, User $user)
    {
        $this->loginForm = $loginForm;
        $this->user = $user;
    }

    public function isLogin(UserRepository $userRepository, string $ip): bool
    {
        $login = $this->loginForm->getLogin();
        $password = $this->loginForm->getAttributeValue('password');
        $user = $userRepository->findUserByUsernameOrEmail($login);

        if ($user === null) {
            $this->loginForm->addError('password', 'Unregistered user/Invalid password.');
        }

        if (
            $user
            && $userRepository->validatePassword(
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

    public function isLoginConfirm(UserAR $user, UserRepository $userRepository, string $ip): bool
    {
        $this->updateAttributeLogin($user, $ip);

        return $this->user->login($user);
    }

    private function updateAttributeLogin(UserAR $user, string $ip): void
    {
        $user->updateAttributes(['ip_last_login' => $ip, 'last_login_at' => time()]);
    }

    private function validateConfirmed(UserAR $user): bool
    {
        $result = true;

        if ($user->getAttribute('confirmed_at') === null) {
            $this->loginForm->addError('password', 'Please check your email to activate your account.');
            $result = false;
        }

        return $result;
    }
}
