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
    private ProfileAR $profile;
    private TokenAR $token;
    private UserAR $user;
    private ?ActiveQuery $userQuery = null;

    public function __construct(
        Aliases $aliases,
        InitialAvatar $avatar,
        ConnectionInterface $db,
        Mailer $mailer,
        LoggerInterface $logger,
        ProfileAR $profile,
        TokenAR $token,
        UserAR $user
    ) {
        $this->aliases = $aliases;
        $this->avatar = $avatar;
        $this->db = $db;
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->token = $token;
        $this->profile = $profile;
        $this->user = $user;
        $this->userQuery();
    }

    public function block(UserAR $user): bool
    {
        return (bool) $user->updateAttributes([
            'blocked_at' => time(),
            'auth_key' => Random::string(),
        ]);
    }

    public function confirm(UserAR $user): bool
    {
        return (bool) $user->updateAttributes(['confirmed_at' => time()]);
    }

    public function create(RegisterForm $registerForm): bool
    {
        if ($this->user->getIsNewRecord() === false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        if ($this->findUserByUsernameOrEmail($registerForm->getEmail())) {
            $registerForm->addError('email', 'Email already registered.');
            return false;
        }

        if ($this->findUserByUsernameOrEmail($registerForm->getUsername())) {
            $registerForm->addError('username', 'Username already registered.');
            return false;
        }

        /** @psalm-suppress UndefinedInterfaceMethod */
        $transaction = $this->db->beginTransaction();

        try {
            $this->insertRecordFromAdminFormAR($registerForm);

            if (!$this->user->save()) {
                $transaction->rollBack();
                return false;
            }

            $this->insertProfile();

            $transaction->commit();

            $result = true;

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->logger->log(LogLevel::WARNING, $e->getMessage());
            throw $e;
        }
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

    public function findUserById(string $id): ?UserAR
    {
        return $this->findUserByCondition(['id' => $id]);
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

    public function generateUrlTokenMailer(UrlGeneratorInterface $url, bool $isConfirmation): ?string
    {
        $urlToken = null;

        if ($isConfirmation === true) {
            $urlToken = $url->generateAbsolute(
                $this->token->toUrl(),
                [
                    'id' => $this->token->getAttribute('user_id'),
                    'code' => $this->token->getAttribute('code')
                ]
            );
        }

        return $urlToken;
    }

    public function loadData(RegisterForm $registerForm, string $id): void
    {
        /** @var UserAR $aqClass */
        $this->user = $this->findUserById($id);

        $registerForm->setAttribute('email', $this->user->getEmail());
        $registerForm->setAttribute('username', $this->user->getUsername());
    }


    public function register(RegisterForm $registerForm, bool $isConfirmation, bool $isGeneratingPassword): bool
    {
        if ($this->user->getIsNewRecord() === false) {
            throw new RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        if ($this->findUserByUsernameOrEmail($registerForm->getEmail())) {
            $registerForm->addError('email', 'Email already registered.');
            return false;
        }

        if ($this->findUserByUsernameOrEmail($registerForm->getUsername())) {
            $registerForm->addError('username', 'Username already registered.');
            return false;
        }

        /** @psalm-suppress UndefinedInterfaceMethod */
        $transaction = $this->db->beginTransaction();

        try {
            $this->insertRecordFromFormAR($registerForm, $isConfirmation, $isGeneratingPassword);

            if (!$this->user->save()) {
                $transaction->rollBack();
                return false;
            }

            if ($isConfirmation === true) {
                $this->insertToken();
            }

            $this->insertProfile();

            $transaction->commit();

            $result = true;
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->logger->log(LogLevel::WARNING, $e->getMessage());
            throw $e;
        }

        return $result;
    }

    /**
     * resetPassword
     *
     * generates a new password and sends it to the user
     */
    public function resetPassword(string $id): bool
    {
        $this->user = $this->findUserById($id);

        $this->user->password($this->generate(8));

        return $this->user->save();
    }

    public function sendMailer(
        UrlGeneratorInterface $url,
        string $subjectWelcome,
        array $layout,
        bool $isConfirmation = false,
        bool $showPassword = true
    ): bool {
        return $this->mailer->run(
            $this->user->getAttribute('email'),
            $subjectWelcome,
            $this->aliases->get('@user/resources/mail'),
            $layout,
            [
                'username' => $this->user->getAttribute('username'),
                'password' => $this->user->getPassword(),
                'url' => $this->generateUrlTokenMailer($url, $isConfirmation),
                'showPassword' => $showPassword
            ]
        );
    }

    /**
     * @param $registerForm $itemForm
     * @param string $id
     *
     * @return bool
     */
    public function update(RegisterForm $registerForm, string $id): bool
    {
        $this->insertRecordFromAdminFormAR($registerForm);

        return $this->user->save();
    }

    public function unblock(UserAr $user): bool
    {
        return (bool) $user->updateAttributes(['blocked_at' => null]);
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

    private function insertProfile(): void
    {
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
    }

    private function insertRecordFromAdminFormAR(RegisterForm $registerForm): void
    {
        $password = empty($registerForm->getAttributeValue('password'))
            ? $this->generate(8)
            : $registerForm->getAttributeValue('password');

        $this->user->username($registerForm->getUsername());
        $this->user->email($registerForm->getEmail());
        $this->user->unconfirmedEmail(null);
        $this->user->password($password);
        $this->user->passwordHash($password);
        $this->user->authKey();
        $this->user->registrationIp($registerForm->getAttributeValue('ip'));
        $this->user->confirmedAt();
        $this->user->createdAt();
        $this->user->updatedAt();
        $this->user->flags(0);
    }

    private function insertRecordFromFormAR(
        RegisterForm $registerForm,
        bool $isConfirmation,
        bool $isGeneratingPassword
    ): void {
        $password = $isGeneratingPassword
            ? $this->generate(8)
            : $registerForm->getAttributeValue('password');

        $this->user->username($registerForm->getUsername());
        $this->user->email($registerForm->getEmail());
        $this->user->unconfirmedEmail(null);
        $this->user->password($password);
        $this->user->passwordHash($password);
        $this->user->authKey();
        $this->user->registrationIp($registerForm->getAttributeValue('ip'));

        if ($isConfirmation === false) {
            $this->user->confirmedAt();
        }

        $this->user->createdAt();
        $this->user->updatedAt();
        $this->user->flags(0);
    }

    private function insertToken(): void
    {
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

    private function userQuery(): ActiveQueryInterface
    {
        if ($this->userQuery === null) {
            $this->userQuery = new ActiveQuery(UserAR::class, $this->db);
        }

        return $this->userQuery;
    }
}
