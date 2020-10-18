<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\Form\LoginForm;
use App\Module\User\Repository\UserRepository;
use Yiisoft\Yii\Web\User\User;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class LoginService
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
        $identity = $identityRepository->findUserByUsernameOrEmail($login);

        if ($identity === null) {
            $this->loginForm->addError('password', 'Unregistered user/Invalid password.');
        }

        /** @var UserAR $identity */
        if (
            $identity
            && $identityRepository->validatePassword(
                $this->loginForm,
                $password,
                $identity->getAttribute('password_hash')
            )
            && $this->validateConfirmed($identity)
        ) {
            $this->updateAttributeLogin($identity, $ip);

            $result = $this->user->login($identity);
        } else {
            $this->loginForm->addError('password', 'Unregistered user/Invalid password.');
            $result = false;
        }

        return $result;
    }

    public function isLoginConfirm(IdentityRepositoryInterface $identityRepository, string $id, string $ip): bool
    {
        $identity = $identityRepository->findIdentity($id);

        $this->updateAttributeLogin($identity, $ip);

        return $this->user->login($identity);
    }

    private function updateAttributeLogin(UserAR $identity, string $ip): void
    {
        $identity->updateAttributes(['ip_last_login' => $ip, 'last_login_at' => time()]);
    }

    private function validateConfirmed(UserAR $identity): bool
    {
        $result = true;

        if ($identity->getAttribute('confirmed_at') === null) {
            $this->loginForm->addError('password', 'Please check your email to activate your account.');
            $result = false;
        }

        return $result;
    }
}
