<?php

declare(strict_types=1);

namespace App\Module\User\ActiveRecord;

use Yiisoft\ActiveRecord\ActiveRecord;

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
 * @property bool    $userNameCaseSensitive
 * @property string  $userNameRegExp
 */
final class ModuleSettingsAR extends ActiveRecord
{
    public function tableName(): string
    {
        return '{{%module_settings}}';
    }
}
