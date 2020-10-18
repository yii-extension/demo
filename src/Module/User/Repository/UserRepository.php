<?php

declare(strict_types=1);

namespace App\Module\User\Repository;

use RuntimeException;
use App\Service\Mailer;
use App\Module\User\ActiveRecord\ProfileAR;
use App\Module\User\ActiveRecord\UserAR;
use App\Module\User\ActiveRecord\TokenAR;
use App\Module\User\Form\RegisterForm;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use Yiisoft\Aliases\Aliases;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Yiisoft\ActiveRecord\ActiveRecordInterface;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
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
    private Aliases $aliases;
    private InitialAvatar $avatar;
    private ConnectionInterface $db;
    private Mailer $mailer;
    private LoggerInterface $logger;
    private RegisterForm $registerForm;
    private ModuleSettingsRepository $settings;
    private ProfileAR $profile;
    private TokenAR $token;
    private UserAR $user;
    private ?ActiveQuery $userQuery = null;
    private UrlGeneratorInterface $url;

    public function __construct(
        Aliases $aliases,
        InitialAvatar $avatar,
        ConnectionInterface $db,
        Mailer $mailer,
        LoggerInterface $logger,
        RegisterForm $registerForm,
        ModuleSettingsRepository $settings,
        ProfileAR $profile,
        TokenAR $token,
        UserAR $user,
        UrlGeneratorInterface $url
    ) {
        $this->aliases = $aliases;
        $this->avatar = $avatar;
        $this->db = $db;
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->token = $token;
        $this->registerForm = $registerForm;
        $this->settings = $settings;
        $this->profile = $profile;
        $this->user = $user;
        $this->url = $url;
        $this->userQuery();
    }

    /**
     * @param string $id
     *
     * @return IdentityInterface|null
     *
     * @psalm-suppress InvalidReturnType, InvalidReturnStatement
     */
    public function findIdentity(string $id): ?IdentityInterface
    {
        return $this->findUserByCondition(['id' => $id]);
    }

    /**
     * @param string $token
     * @param string|null $type
     *
     * @return IdentityInterface|null
     *
     * @psalm-suppress InvalidReturnType, InvalidReturnStatement
     */
    public function findIdentityByToken(string $token, ?string $type = null): ?IdentityInterface
    {
        return $this->findUserByCondition(['auth_key' => $token]);
    }

    public function findUserAll(): array
    {
        return $this->userQuery->all();
    }

    public function findUserAllAsArray(): array
    {
        return $this->userQuery->asArray()->all();
    }

    public function findUserByCondition(array $condition): ?ActiveRecordInterface
    {
        return $this->userQuery->findOne($condition);
    }

    public function findUserByEmail(string $email): ?ActiveRecordInterface
    {
        return $this->findUserByCondition(['email' => $email]);
    }

    public function findUserByUsername(string $username): ?ActiveRecordInterface
    {
        return $this->findUserByCondition(['username' => $username]);
    }

    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?ActiveRecordInterface
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    public function getMailUrlToken(): ?string
    {
        $url = null;

        if ($this->settings->isConfirmation() === true) {
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

            if ($this->settings->isConfirmation() === true) {
                $this->token->setAttribute('type', TokenAR::TYPE_CONFIRMATION);

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

            $this->profile->setAttribute(
                'avatar',
                $this->avatar->name($this->user->getAttribute('username'))
                    ->length(2)
                    ->fontSize(0.5)
                    ->size(28)
                    ->background('#1e6887')
                    ->color('#fff')
                    ->rounded()
                    ->generateSvg()
                    ->toXMLString()
            );

            $this->profile->link('user', $this->user);

            $transaction->commit();

            $result = true;
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->logger->log(LogLevel::WARNING, $e->getMessage());
            throw $e;
        }

        return $result;
    }

    public function sendMailer(): bool
    {
        return $this->mailer->run(
            $this->user->getAttribute('email'),
            $this->settings->getSubjectWelcome(),
            $this->aliases->get('@user/resources/mail'),
            ['html' => 'welcome', 'text' => 'text/welcome'],
            [
                'username' => $this->user->getAttribute('username'),
                'password' => $this->user->getPassword(),
                'url' => $this->getMailUrlToken(),
                'showPassword' => $this->settings->isGeneratingPassword()
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
        $password = $this->settings->isGeneratingPassword()
            ? $this->generate(8)
            : $this->registerForm->getAttributeValue('password');

        $this->user->username($this->registerForm->getAttributeValue('username'));
        $this->user->email($this->registerForm->getAttributeValue('email'));
        $this->user->unconfirmedEmail(null);
        $this->user->password($password);
        $this->user->passwordHash($password);
        $this->user->authKey();
        $this->user->registrationIp($this->registerForm->getAttributeValue('ip'));

        if ($this->settings->isConfirmation() === false) {
            $this->user->confirmedAt();
        }

        $this->user->createdAt();
        $this->user->updatedAt();
        $this->user->flags(0);
    }

    private function userQuery(): ActiveQueryInterface
    {
        if ($this->userQuery === null) {
            $this->userQuery = new ActiveQuery(UserAR::class, $this->db);
        }

        return $this->userQuery;
    }
}
