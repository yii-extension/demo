<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\ActiveRecord\TokenAR;
use App\Module\User\Form\ResendForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\TokenRepository;
use App\Module\User\Repository\UserRepository;

final class ResendService
{
    private ModuleSettingsRepository $settings;
    private TokenRepository $tokenRepository;

    public function __construct(ModuleSettingsRepository $settings, TokenRepository $tokenRepository)
    {
        $this->settings = $settings;
        $this->tokenRepository = $tokenRepository;
    }

    public function run(ResendForm $resendForm, UserRepository $userRepository): bool
    {
        $result = false;

        $email = $resendForm->getAttributeValue('email');
        $user = $userRepository->findUserByUsernameOrEmail($email);

        if ($user === null) {
            $resendForm->addError('email', 'Thank you. If said email is registered, you will get a password reset.');

            return $result;
        }

        /** @var UserAR $user */
        if ($user->getAttribute('confirmed_at') !== null) {
            $resendForm->addError('email', 'User is active.');

            return $result;
        }

        /** @var UserAR $user */
        if (
            !$user->isConfirmed()
            && $this->tokenRepository->register($user->getAttribute('id'), TokenAR::TYPE_CONFIRMATION)
        ) {
            $result = $this->tokenRepository->sendMailer(
                $user->getAttribute('id'),
                $user->getAttribute('email'),
                $user->getAttribute('username'),
                $this->settings->getSubjectConfirm(),
                ['html' => 'confirmation', 'text' => 'text/confirmation'],
            );
        }

        return $result;
    }
}
