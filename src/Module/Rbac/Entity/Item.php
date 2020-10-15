<?php

declare(strict_types=1);

namespace App\Module\User\Entity;

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
final class Item extends ActiveRecord
{
    public function tableName(): string
    {
        return '{{%item}}';
    }
}
