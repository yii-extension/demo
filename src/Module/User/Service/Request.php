<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\Entity\User;
use App\Module\User\Entity\Token;
use App\Module\User\Form\Request as RequestForm;
use App\Module\User\Repository\TokenRepository;
use App\Module\User\Repository\UserRepository;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class Request
{
    private TokenRepository $tokenRepository;

    public function __construct(TokenRepository $tokenRepository) {
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

        /** @var User $identity  */
        if ($identity->getAttribute('confirmed_at') === null) {
            $requestForm->addError('email', 'Inactive user.');

            return $result;
        }

        if ($this->tokenRepository->register($identity->getAttribute('id'), Token::TYPE_RECOVERY)) {
            $result = $this->tokenRepository->sendEmail(
                $identity->getAttribute('id'),
                $identity->getAttribute('email'),
                $identity->getAttribute('username'),
                'user.subjectRecovery',
                ['html' => 'recovery', 'text' => 'text/recovery']
            );
        }

        return $result;
    }
}
