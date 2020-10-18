<?php

declare(strict_types=1);

namespace App\Module\User\ActiveRecord;

use RuntimeException;
use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\ActiveQuery;

/**
 * TokenAR Active Record - Module AR User.
 *
 * Database fields:
 * @property integer $user_id
 * @property string  $code
 * @property integer $created_at
 * @property integer $type
 * @property string  $url
 * @property bool    $isExpired
 */
final class TokenAR extends ActiveRecord
{
    public const TYPE_CONFIRMATION      = 0;
    public const TYPE_RECOVERY          = 1;
    public const TYPE_CONFIRM_NEW_EMAIL = 2;
    public const TYPE_CONFIRM_OLD_EMAIL = 3;

    public function tableName(): string
    {
        return '{{%token}}';
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(UserAR::class, ['id' => 'user_id']);
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function toUrl(): string
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
                $route = 'registration/confirm';
                break;
            case self::TYPE_RECOVERY:
                $route = 'recovery/reset';
                break;
            case self::TYPE_CONFIRM_NEW_EMAIL:
            case self::TYPE_CONFIRM_OLD_EMAIL:
                $route = 'setting/confirm';
                break;
            default:
                throw new RuntimeException('Url not available.');
        }

        return $route;
    }

    public function isExpired(int $tokenConfirmWithin = 0, int $tokenRecoverWithin = 0): bool
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
            case self::TYPE_CONFIRM_NEW_EMAIL:
            case self::TYPE_CONFIRM_OLD_EMAIL:
                $expirationTime = $tokenConfirmWithin;
                break;
            case self::TYPE_RECOVERY:
                $expirationTime = $tokenRecoverWithin;
                break;
            default:
                throw new RuntimeException('Expired not available.');
        }

        return ($this->created_at + $expirationTime) < time();
    }

    public function primaryKey(): array
    {
        return ['user_id', 'code', 'type'];
    }
}
