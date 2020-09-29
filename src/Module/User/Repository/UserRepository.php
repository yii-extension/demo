<?php

declare(strict_types=1);

namespace App\Module\User\Repository;

use RuntimeException;
use App\Service\Mailer;
use App\Service\Parameters;
use App\Module\User\Entity\User;
use App\Module\User\Entity\Token;
use App\Module\User\Form\Register as RegisterForm;
use Yiisoft\Aliases\Aliases;
use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Form\FormModelInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Security\PasswordHasher;
use Yiisoft\Security\Random;

use function array_rand;
use function count;
use function filter_var;
use function str_shuffle;
use function str_split;

final class UserRepository implements IdentityRepositoryInterface
{
    private Parameters $app;
    private Aliases $aliases;
    private ConnectionInterface $db;
    private Mailer $mailer;
    private RegisterForm $registerForm;
    private Token $token;
    private User $user;
    private UrlGeneratorInterface $url;

    public function __construct(
        Parameters $app,
        Aliases $aliases,
        ConnectionInterface $db,
        Mailer $mailer,
        RegisterForm $registerForm,
        Token $token,
        User $user,
        UrlGeneratorInterface $url
    ) {
        $this->app = $app;
        $this->aliases = $aliases;
        $this->db = $db;
        $this->mailer = $mailer;
        $this->token = $token;
        $this->registerForm = $registerForm;
        $this->user = $user;
        $this->url = $url;
    }

    /** @psalm-suppress InvalidReturnType, InvalidReturnStatement */
    public function findIdentity(string $id): ?IdentityInterface
    {
        return $this->user->findOne(['id' => $id]);
    }

    /** @psalm-suppress InvalidReturnType, InvalidReturnStatement */
    public function findIdentityByToken(string $token, ?string $type = null): ?IdentityInterface
    {
        return $this->user->findOne(['auth_key' => $token]);
    }

    public function findUserByEmail(string $email): ?ActiveRecord
    {
        return $this->user->findOne(['email' => $email]);
    }

    public function findUserById(int $id): ?ActiveRecord
    {
        return $this->user->findOne(['id' => $id]);
    }

    public function findUserByUsername(string $username): ?ActiveRecord
    {
        return $this->user->findOne(['username' => $username]);
    }

    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?ActiveRecord
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    public function getMailUrlToken(): ?string
    {
        $url = null;

        if ($this->app->get('user.confirmation') === true) {
            $url = $this->url->generateAbsolute(
                $this->token->toUrl(),
                [
                    'id' => $this->token->getAttribute('user_id'),
                    'code' => $this->token->getAttribute('code')
                ]
            );
        }

        return $url;
    }

    public function register(): bool
    {
        if ($this->user->getIsNewRecord() === false) {
            throw new RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        if ($this->findUserByUsernameOrEmail($this->registerForm->getAttributeValue('email'))) {
            $this->registerForm->addError('email', 'Email already registered.');
            return false;
        }

        if ($this->findUserByUsernameOrEmail($this->registerForm->getAttributeValue('username'))) {
            $this->registerForm->addError('username', 'Username already registered.');
            return false;
        }

        /** @psalm-suppress UndefinedInterfaceMethod */
        $transaction = $this->db->beginTransaction();

        try {
            $this->insertRecordFromFormModel();

            if (!$this->user->save()) {
                $transaction->rollBack();
                return false;
            }

            if ($this->app->get('user.confirmation') === true) {
                $this->token->setAttribute('type', Token::TYPE_CONFIRMATION);

                $this->token->deleteAll(
                    [
                        'user_id' => $this->token->getAttribute('user_id'),
                        'type' => $this->token->getAttribute('type')
                    ]
                );

                $this->token->setAttribute('created_at', time());
                $this->token->setAttribute('code', Random::string());

                $this->token->link('user', $this->user);
            }

            $transaction->commit();

            $result = true;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $result;
    }

    public function sendMailer(): bool
    {
        return $this->mailer->run(
            $this->user->getAttribute('email'),
            $this->app->get('user.subjectWelcome'),
            $this->aliases->get('@user/resources/mail'),
            ['html' => 'welcome', 'text' => 'text/welcome'],
            [
                'username' => $this->user->getAttribute('username'),
                'password' => $this->user->getPassword(),
                'url' => $this->getMailUrlToken(),
                'showPassword' => $this->app->get('user.generatingPassword')
            ]
        );
    }

    public function validatePassword(FormModelInterface $form, string $password, string $password_hash): bool
    {
        $result = true;

        if (!(new PasswordHasher())->validate($password, $password_hash)) {
            $form->addError('currentPassword', 'Invalid password.');
            $result = false;
        }

        return $result;
    }

    /**
     * Generate password.
     *
     * generates user-friendly random password containing at least one lower case letter, one uppercase letter and one
     * digit. The remaining characters in the password are chosen at random from those three sets
     *
     * @param int $length
     * @return string
     *
     * {@see https://gist.github.com/tylerhall/521810}
     */
    private function generate(int $length): string
    {
        $sets = [
            'abcdefghjkmnpqrstuvwxyz',
            'ABCDEFGHJKMNPQRSTUVWXYZ',
            '23456789',
        ];
        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        }

        $password = str_shuffle($password);
        return $password;
    }

    private function insertRecordFromFormModel(): void
    {
        $password = $this->app->get('user.generatingPassword')
            ? $this->generate(8)
            : $this->registerForm->getAttributeValue('password');

        $this->user->username($this->registerForm->getAttributeValue('username'));
        $this->user->email($this->registerForm->getAttributeValue('email'));
        $this->user->unconfirmedEmail(null);
        $this->user->password($password);
        $this->user->passwordHash($password);
        $this->user->authKey();
        $this->user->registrationIp($this->registerForm->getAttributeValue('ip'));

        if ($this->app->get('user.confirmation') === false) {
            $this->user->confirmedAt();
        }

        $this->user->createdAt();
        $this->user->updatedAt();
        $this->user->flags(0);
    }
}
