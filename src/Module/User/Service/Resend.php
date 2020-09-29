<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use RuntimeException;
use App\Module\User\Entity\User;
use App\Module\User\Form\Resend as ResendForm;
use App\Module\User\Repository\TokenRepository;
use App\Module\User\Repository\UserRepository;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class Resend
{
    private TokenRepository $tokenRepository;

    public function __construct(TokenRepository $tokenRepository) {
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

        if ($identity->getAttribute('confirmed_at') !== null) {
            $resendForm->addError('email', 'User is active.');

            return $result;
        }

        $token = $this->tokenRepository->findTokenById($identity->getAttribute('id'));

        /** @var User $identity */
        if (!$identity->isConfirmed() && $token === null) {
            $this->tokenRepository->register($identity->getAttribute('id'));
        }

        $result = $this->tokenRepository->sendEmail(
            $identity->getAttribute('id'),
            $identity->getAttribute('email'),
            $identity->getAttribute('username')
        );

        return $result;
    }
}
