<?php

declare(strict_types=1);

namespace App\Module\User\Repository;

use App\Module\User\ActiveRecord\ModuleSettingsAR;
use App\Module\User\Form\SettingsForm;
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

    public function isDelete(): bool
    {
        return $this->settings->getAttribute('delete');
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

    public function getEmailFrom(): string
    {
        return $this->settings->getAttribute('emailFrom');
    }

    public function getMessageHeader(): string
    {
        return $this->settings->getAttribute('messageHeader');
    }

    public function getSubjectConfirm(): string
    {
        return $this->settings->getAttribute('subjectConfirm');
    }

    public function getSubjectPassword(): string
    {
        return $this->settings->getAttribute('subjectPassword');
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

    public function getUsernameCaseSensitive(): bool
    {
        return $this->settings->getAttribute('userNameCaseSensitive');
    }

    public function getUsernameRegExp(): string
    {
        return $this->settings->getAttribute('userNameRegExp');
    }

    public function accountDelete(bool $value): void
    {
        $this->settings->setAttribute('delete', $value);
    }

    public function confirmation(bool $value): void
    {
        $this->settings->setAttribute('confirmation', $value);
    }

    public function emailFrom(string $value): void
    {
        $this->settings->setAttribute('emailFrom', $value);
    }

    public function generatingPassword(bool $value): void
    {
        $this->settings->setAttribute('generatingPassword', $value);
    }

    public function messageHeader(string $value): void
    {
        $this->settings->setAttribute('messageHeader', $value);
    }

    public function passwordRecovery(bool $value): void
    {
        $this->settings->setAttribute('passwordRecovery', $value);
    }

    public function register(bool $value): void
    {
        $this->settings->setAttribute('register', $value);
    }

    public function subjectConfirm(string $value): void
    {
        $this->settings->setAttribute('subjectConfirm', $value);
    }

    public function subjectPassword(string $value): void
    {
        $this->settings->setAttribute('subjectPassword', $value);
    }

    public function subjectReconfirmation(string $value): void
    {
        $this->settings->setAttribute('subjectReconfirmation', $value);
    }

    public function subjectRecovery(string $value): void
    {
        $this->settings->setAttribute('subjectRecovery', $value);
    }

    public function subjectWelcome(string $value): void
    {
        $this->settings->setAttribute('subjectWelcome', $value);
    }

    public function tokenConfirmWithin(int $value): void
    {
        $this->settings->setAttribute('tokenConfirmWithin', $value);
    }

    public function tokenRecoverWithin(int $value): void
    {
        $this->settings->setAttribute('tokenRecoverWithin', $value);
    }

    public function userNameCaseSensitive(bool $value): void
    {
        $this->settings->setAttribute('userNameCaseSensitive', $value);
    }

    public function userNameRegExp(string $value): void
    {
        $this->settings->setAttribute('userNameRegExp', $value);
    }

    public function loadData(SettingsForm $settingsForm): void
    {
        $settingsForm->setAttribute('confirmation', $this->isConfirmation());
        $settingsForm->setAttribute('delete', $this->isDelete());
        $settingsForm->setAttribute('emailFrom', $this->getEmailFrom());
        $settingsForm->setAttribute('generatingPassword', $this->isGeneratingPassword());
        $settingsForm->setAttribute('messageHeader', $this->getMessageHeader());
        $settingsForm->setAttribute('passwordRecovery', $this->isPasswordRecovery());
        $settingsForm->setAttribute('register', $this->isRegister());
        $settingsForm->setAttribute('subjectConfirm', $this->getSubjectConfirm());
        $settingsForm->setAttribute('subjectPassword', $this->getSubjectPassword());
        $settingsForm->setAttribute('tokenConfirmWithin', $this->getTokenConfirmWithin());
        $settingsForm->setAttribute('tokenRecoverWithin', $this->getTokenRecoverWithin());
        $settingsForm->setAttribute('userNameCaseSensitive', $this->getUserNameCaseSensitive());
        $settingsForm->setAttribute('userNameRegExp', $this->getUserNameRegExp());
    }

    public function update(SettingsForm $settingForm): bool
    {
        $this->register($settingForm->getAttributeValue('register'));
        $this->confirmation($settingForm->getAttributeValue('confirmation'));
        $this->passwordRecovery($settingForm->getAttributeValue('passwordRecovery'));
        $this->generatingPassword($settingForm->getAttributeValue('generatingPassword'));
        $this->userNameCaseSensitive($settingForm->getAttributeValue('userNameCaseSensitive'));
        $this->accountDelete($settingForm->getAttributeValue('delete'));
        $this->emailFrom($settingForm->getAttributeValue('emailFrom'));
        $this->messageHeader($settingForm->getAttributeValue('messageHeader'));
        $this->tokenConfirmWithin($settingForm->getAttributeValue('tokenConfirmWithin'));
        $this->tokenRecoverWithin($settingForm->getAttributeValue('tokenRecoverWithin'));
        $this->userNameRegExp($settingForm->getAttributeValue('userNameRegExp'));

        return (bool) $this->settings->update();
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
