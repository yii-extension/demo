<?php

declare(strict_types=1);

namespace App\Module\Rbac\Migration;

use Yiisoft\Yii\Db\Migration\Migration;
use Yiisoft\Yii\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M201015154349CreateItem
 */
final class M201015154349CreateItem extends Migration implements RevertibleMigrationInterface
{
    public function up(): void
    {
        $tableOptions = null;

        if ($this->db->getDriverName() === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            'item',
            [
                'id' => $this->primaryKey()->notNull(),
                'name' => $this->string(255)->notNull(),
                'description' => $this->string(255),
                'type' => $this->string(10)->notNull(),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull()
            ],
            $tableOptions
        );
    }

    public function down(): void
    {
        $this->dropTable('item');
    }
}
