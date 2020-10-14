<?php

declare(strict_types=1);

namespace App\Module\User\Repository;

use App\Module\User\Entity\ModuleSettings as ModuleSettingsEntity;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\Db\Connection\ConnectionInterface;

final class ModuleSettings
{
    private ConnectionInterface $db;
    private ModuleSettingsEntity $settings;
    private ?ActiveQueryInterface $moduleSettingsQuery = null;
    private ModuleSettingsEntity $moduleSettings;

    public function __construct(ConnectionInterface $db, ModuleSettingsEntity $moduleSettings)
    {
        $this->db = $db;
        $this->moduleSettings = $moduleSettings;
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

    private function loadSettings()
    {
        return $this->settings = $this->moduleSettingsQuery->findOne(1);
    }

    private function moduleSettingsQuery(): ActiveQueryInterface
    {
        if ($this->moduleSettingsQuery === null) {
            $this->moduleSettingsQuery = new ActiveQuery(ModuleSettingsEntity::class, $this->db);
        }

        return $this->moduleSettingsQuery;
    }
}
