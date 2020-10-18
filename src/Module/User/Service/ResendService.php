<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\ActiveRecord\TokenAR;
use App\Module\User\Form\ResendForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\TokenRepository;
use App\Module\User\Repository\UserRepository;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class ResendService
{
    private ModuleSettingsRepository $settings;
    private TokenRepository $tokenRepository;

    public function __construct(ModuleSettingsRepository $settings, TokenRepository $tokenRepository)
    {
        $this->settings = $settings;
        $this->tokenRepository = $tokenRepository;
    }

    public function run(ResendForm $resendForm, IdentityRepositoryInterface $identityRepository): bool
    {
        $result = false;

        $email = $resendForm->getAttributeValue('email');

        /** @var UserRepository $identityRepository */
        $identity = $identityRepository->findUserByUsernameOrEmail($email);

        if ($identity === null) {
            $resendForm->addError('email', 'Email not registered.');

            return $result;
        }

        /** @var UserAR $identity */
        if ($identity->getAttribute('confirmed_at') !== null) {
            $resendForm->addError('email', 'User is active.');

            return $result;
        }

        /** @var UserAR $identity */
        if (
            !$identity->isConfirmed()
            && $this->tokenRepository->register($identity->getAttribute('id'), TokenAR::TYPE_CONFIRMATION)
        ) {
            $result = $this->tokenRepository->sendMailer(
                $identity->getAttribute('id'),
                $identity->getAttribute('email'),
                $identity->getAttribute('username'),
                $this->settings->getSubjectConfirm(),
                ['html' => 'confirmation', 'text' => 'text/confirmation'],
            );
        }

        return $result;
    }
}
