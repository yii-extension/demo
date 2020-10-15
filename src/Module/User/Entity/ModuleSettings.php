<?php

declare(strict_types=1);

namespace App\Module\User\Entity;

use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\ActiveQuery;

/**
 * Module Setting Active Record - Module AR User.
 *
 * Database fields:
 * @property integer $id
 * @property bool    $confirmation
 * @property string  $emailFrom
 * @property bool    $generatingPassword
 * @property string  $messageHeader
 * @property bool    $passwordRecovery
 * @property bool    $register
 * @property string  $subjectConfirm
 * @property string  $subjectPassword
 * @property string  $subjectReconfirmation
 * @property string  $subjectRecovery
 * @property string  $subjectWelcome
 * @property int     $tokenConfirmWithin
 * @property int     $tokenRecoverWithin
 * @property string  $usernameRegExp
 */
final class ModuleSettings extends ActiveRecord
{
    public function tableName(): string
    {
        return '{{%module_settings}}';
    }
}
