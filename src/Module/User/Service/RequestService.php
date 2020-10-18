<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\ActiveRecord\TokenAR;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Form\RequestForm;
use App\Module\User\Repository\TokenRepository;
use App\Module\User\Repository\UserRepository;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class RequestService
{
    private ModuleSettingsRepository $settings;
    private TokenRepository $tokenRepository;

    public function __construct(ModuleSettingsRepository $settings, TokenRepository $tokenRepository)
    {
        $this->settings = $settings;
        $this->tokenRepository = $tokenRepository;
    }

    public function run(RequestForm $requestForm, IdentityRepositoryInterface $identityRepository): bool
    {
        $result = false;

        $email = $requestForm->getAttributeValue('email');

        /** @var UserRepository $identityRepository */
        $identity = $identityRepository->findUserByUsernameOrEmail($email);

        if ($identity === null) {
            $requestForm->addError('email', 'Email not registered.');

            return $result;
        }

        /** @var UserAR $identity  */
        if ($identity->getAttribute('confirmed_at') === null) {
            $requestForm->addError('email', 'Inactive user.');

            return $result;
        }

        if ($this->tokenRepository->register($identity->getAttribute('id'), TokenAR::TYPE_RECOVERY)) {
            $result = $this->tokenRepository->sendMailer(
                $identity->getAttribute('id'),
                $identity->getAttribute('email'),
                $identity->getAttribute('username'),
                $this->settings->getSubjectRecovery(),
                ['html' => 'recovery', 'text' => 'text/recovery']
            );
        }

        return $result;
    }
}
