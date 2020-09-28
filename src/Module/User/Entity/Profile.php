<?php

declare(strict_types=1);

namespace App\Module\User\Entity;

use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\ActiveQuery;

/**
 * Token Active Record model.
 *
 * Database fields:
 * @property integer $user_id
 * @property string  $name
 * @property string  $public_email
 * @property string  $location
 * @property string  $website
 * @property string  $bio
 * @property string  $timezone
 */
final class Profile extends ActiveRecord
{
    public function tableName(): string
    {
        return '{{%profile}}';
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function name(?string $value): void
    {
        $this->setAttribute('name', $value);
    }

    public function publicEmail(?string $value): void
    {
        $this->setAttribute('public_email', $value);
    }

    public function location(?string $value): void
    {
        $this->setAttribute('location', $value);
    }

    public function website(?string $value): void
    {
        $this->setAttribute('website', $value);
    }

    public function bio(?string $value): void
    {
        $this->setAttribute('bio', $value);
    }

    public function timezone(?string $value): void
    {
        $this->setAttribute('timezone', $value);
    }
}
