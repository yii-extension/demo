<?php

declare(strict_types=1);

namespace App\Module\User\Entity;

use Yiisoft\Auth\IdentityInterface;
use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\Security\PasswordHasher;
use Yiisoft\Security\Random;

/**
 * Entity User.
 *
 * User ActiveRecord:
 * @property bool $isAdmin
 * @property bool $isBlocked
 * @property bool $isConfirmed
 *
 * Database fields:
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $unconfirmed_email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $registration_ip
 * @property int $confirmed_at
 * @property int $blocked_at
 * @property int $created_at
 * @property int $updated_at
 * @property int $last_login_at
 * @property int $flags
 *
 * Defined relations:
 * @property Profile $profile
 **/
final class User extends ActiveRecord implements IdentityInterface
{
    public const UNCONFIRMEDEMAIL = 'Not confirmed';
    private string $password;

    public function tableName(): string
    {
        return '{{%user}}';
    }

    public function isConfirmed(): bool
    {
        return $this->getAttribute('confirmed_at') !== null;
    }

    public function getId(): ?string
    {
        return $this->getAttribute('id') === null ? null : (string)$this->getAttribute('id');
    }

    public function getEmail(): string
    {
        return $this->getAttribute('email');
    }

    public function getFlags(): int
    {
        return $this->flags;
    }

    public function getUsername(): string
    {
        return $this->getAttribute('username');
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordHash(): ?string
    {
        return $this->getAttribute('password_hash');
    }

    public function getUnconfirmedEmail(): ?string
    {
        return $this->getAttribute('unconfirmed_email');
    }

    public function username(string $value): void
    {
        $this->setAttribute('username', $value);
    }

    public function email(string $value): void
    {
        $this->setAttribute('email', $value);
    }

    public function unconfirmedEmail(?string $value): void
    {
        $this->setAttribute('unconfirmed_email', $value);
    }

    public function password(string $value): void
    {
        $this->password = $value;
    }

    public function passwordHash(string $value): void
    {
        $this->setAttribute('password_hash', (new PasswordHasher(PASSWORD_ARGON2I))->hash($value));
    }

    public function passwordHashUpdate(string $value): void
    {
        $this->updateAttributes(['password_hash' => (new PasswordHasher(PASSWORD_ARGON2I))->hash($value)]);
    }

    public function authKey(): void
    {
        $this->setAttribute('auth_key', Random::string());
    }

    public function registrationIp(string $value): void
    {
        $this->setAttribute('registration_ip', $value);
    }

    public function confirmedAt(): void
    {
        $this->setAttribute('confirmed_at', time());
    }

    public function createdAt(): void
    {
        $this->setAttribute('created_at', time());
    }

    public function updatedAt(): void
    {
        $this->setAttribute('updated_at', time());
    }

    public function flags(int $value): void
    {
        $this->setAttribute('flags', $value);
    }
}
