<?php

declare(strict_types=1);

namespace App\Module\Rbac\ActiveRecord;

use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\ActiveQuery;

/**
 * Item Active Record - Module Rbac Db.
 *
 * Database fields:
 * @property integer $id
 * @property string  $name
 * @property string  $description
 * @property string  $type
 * @property int     $created_at
 * @property int     $updated_at
 */
final class ItemAR extends ActiveRecord
{
    public function tableName(): string
    {
        return '{{%item}}';
    }

    public function name(string $name): void
    {
        $this->setAttribute('name', $name);
    }

    public function description(string $description): void
    {
        $this->setAttribute('description', $description);
    }

    public function type(string $type): void
    {
        $this->setAttribute('type', $type === '1' ? 'role' : 'permission');
    }

    public function createdAt(): void
    {
        $this->setAttribute('created_at', time());
    }

    public function updatedAt(): void
    {
        $this->setAttribute('updated_at', time());
    }
}
