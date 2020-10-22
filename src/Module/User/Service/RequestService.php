<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\ActiveRecord\TokenAR;
use App\Module\User\Form\RequestForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\TokenRepository;
use App\Module\User\Repository\UserRepository;

final class RequestService
{
    private ModuleSettingsRepository $settings;
    private TokenRepository $tokenRepository;

    public function __construct(ModuleSettingsRepository $settings, TokenRepository $tokenRepository)
    {
        $this->settings = $settings;
        $this->tokenRepository = $tokenRepository;
    }

    public function run(RequestForm $requestForm, UserRepository $userRepository): bool
    {
        $result = false;

        $email = $requestForm->getAttributeValue('email');
        $user = $userRepository->findUserByUsernameOrEmail($email);

        if ($user === null) {
            $requestForm->addError('email', 'Email not registered.');

            return $result;
        }

        /** @var UserAR $user  */
        if ($user->getAttribute('confirmed_at') === null) {
            $requestForm->addError('email', 'Inactive user.');

            return $result;
        }

        if ($this->tokenRepository->register($user->getAttribute('id'), TokenAR::TYPE_RECOVERY)) {
            $result = $this->tokenRepository->sendMailer(
                $user->getAttribute('id'),
                $user->getAttribute('email'),
                $user->getAttribute('username'),
                $this->settings->getSubjectRecovery(),
                ['html' => 'recovery', 'text' => 'text/recovery']
            );
        }

        return $result;
    }
}
