<?php

declare(strict_types=1);

namespace App\Module\User\Repository;

use App\Module\User\ActiveRecord\ModuleSettingsAR;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecordInterface;
use Yiisoft\Db\Connection\ConnectionInterface;

final class ModuleSettingsRepository
{
    private ConnectionInterface $db;
    private ?ModuleSettingsAR $settings;
    private ?Activequery $moduleSettingsQuery = null;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
        $this->moduleSettingsQuery();
        $this->loadSettings();
    }

    public function isConfirmation(): bool
    {
        return $this->settings->getAttribute('confirmation');
    }

    public function isGeneratingPassword(): bool
    {
        return $this->settings->getAttribute('generatingPassword');
    }

    public function isPasswordRecovery(): bool
    {
        return $this->settings->getAttribute('passwordRecovery');
    }

    public function isRegister(): bool
    {
        return $this->settings->getAttribute('register');
    }

    public function getMessageHeader(): string
    {
        return $this->settings->getAttribute('messageHeader');
    }

    public function getSubjectConfirm(): string
    {
        return $this->settings->getAttribute('subjectConfirm');
    }

    public function getSubjectWelcome(): string
    {
        return $this->settings->getAttribute('subjectWelcome');
    }

    public function getSubjectRecovery(): string
    {
        return $this->settings->getAttribute('subjectRecovery');
    }

    public function getTokenConfirmWithin(): int
    {
        return $this->settings->getAttribute('tokenConfirmWithin');
    }

    public function getTokenRecoverWithin(): int
    {
        return $this->settings->getAttribute('tokenRecoverWithin');
    }

    public function getUsernameRegExp(): string
    {
        return $this->settings->getAttribute('usernameRegExp');
    }

    private function loadSettings(): ?ActiveRecordInterface
    {
        return $this->settings = $this->moduleSettingsQuery->findOne(1);
    }

    private function moduleSettingsQuery(): ActiveQueryInterface
    {
        if ($this->moduleSettingsQuery === null) {
            $this->moduleSettingsQuery = new ActiveQuery(ModuleSettingsAR::class, $this->db);
        }

        return $this->moduleSettingsQuery;
    }
}
