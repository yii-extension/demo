<?php

declare(strict_types=1);

namespace Yii\Migration;

use Yiisoft\Yii\Db\Migration\Migration;
use Yiisoft\Yii\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M201014141725CreateModuleSettings
 */
final class M201014141725CreateModuleSettings extends Migration implements RevertibleMigrationInterface
{
    public function up(): void
    {
        $tableOptions = null;

        if ($this->db->getDriverName() === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            'module_settings',
            [
                'id' => $this->primaryKey(),
                'confirmation' => $this->boolean(),
                'emailFrom' => $this->string(45),
                'generatingPassword' => $this->boolean(),
                'messageHeader' => $this->string(100),
                'passwordRecovery' => $this->boolean(),
                'register' => $this->boolean(),
                'subjectConfirm' => $this->string(100),
                'subjectPassword' => $this->string(100),
                'subjectReconfirmation' => $this->string(100),
                'subjectRecovery' => $this->string(100),
                'subjectWelcome' => $this->string(100),
                'tokenConfirmWithin' => $this->integer(),
                'tokenRecoverWithin' => $this->integer(),
                'usernameRegExp' => $this->string(25)
            ],
            $tableOptions
        );

        $this->createIndex('id', 'module_settings', ['id'], true);

        $this->batchInsert(
            'module_settings',
            [
                'confirmation',
                'emailFrom',
                'generatingPassword',
                'messageHeader',
                'passwordRecovery',
                'register',
                'subjectConfirm',
                'subjectPassword',
                'subjectReconfirmation',
                'subjectRecovery',
                'subjectWelcome',
                'tokenConfirmWithin',
                'tokenRecoverWithin',
                'usernameRegExp'
            ],
            [
                [
                    false,
                    'support@example.com',
                    false,
                    'System Notification - Yii Demo User Module AR.',
                    true,
                    true,
                    'Confirm account.',
                    'Your password has been changed.',
                    'Confirm email change.',
                    'Complete password reset.',
                    'Welcome.',
                    86400,
                    86400,
                    '/^[-a-zA-Z0-9_\.@]+$/'
                ]
            ]
        );
    }

    public function down(): void
    {
        $this->dropTable('module_setting');
    }
}
