<?php

declare(strict_types=1);

namespace App\Module\User\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Required;

final class SettingsForm extends FormModel
{
    private bool $confirmation = false;
    private bool $delete = false;
    private string $emailFrom = '';
    private bool $generatingPassword = false;
    private string $messageHeader = '';
    private bool $passwordRecovery = true;
    private bool $register = true;
    private string $subjectConfirm = '';
    private string $subjectPassword = '';
    private string $subjectReconfirmation = '';
    private string $subjectRecovery = '';
    private string $subjectWelcome = '';
    private int $tokenConfirmWithin = 0;
    private int $tokenRecoverWithin = 0;
    private bool $userNameCaseSensitive = true;
    private string $userNameRegExp = '';

    public function formName(): string
    {
        return 'Settings';
    }

    public function confirmationHelp(): string
    {
        return 'If this option is set to true, module sends email that contains a confirmation ' . "\n" .
            'link that user must click to complete registration.';
    }

    public function emailFromHelp(): string
    {
        return 'The email address from where they will be sent.';
    }

    public function generatingPasswordHelp(): string
    {
        return 'If this option is set to true, password field on registration page will be hidden ' . "\n" .
            'and password for user will be generated automatically. ' . "\n" .
            'Generated password will be 8 characters long and will be sent to user via email.';
    }


    public function passwordRecoveryHelp(): string
    {
        return 'If this option is to true, users will be able to recovery their forgotten passwords.';
    }

    public function registerHelp(): string
    {
        return 'If this option is set to false, users will not be able to register an account. ' . "\n" .
            'Registration page will throw HttpNotFoundException. ' . "\n" .
            'However confirmation will continue working and you as an administrator will be able to ' . "\n" .
            'create an account for user from admin interface.';
    }

    public function userNameCaseSensitiveHelp(): string
    {
        return 'If this option is set to true, difference between upper and lower case, for username.';
    }

    public function userNameRegExpHelp(): string
    {
        return 'Default username regex expression validation.';
    }

    public function messageHeaderHelp(): string
    {
        return 'The header that will be displayed in flash messages.';
    }

    public function subjectConfirmHelp(): string
    {
        return 'The subject of the confirmation email.';
    }

    public function subjectPasswordHelp(): string
    {
        return 'The subject of the new password email message.';
    }

    public function subjectReconfirmationHelp(): string
    {
        return 'The subject of the change email message.';
    }

    public function subjectRecoveryHelp(): string
    {
        return 'The subject of the recovery password message.';
    }

    public function subjectWelcomeHelp(): string
    {
        return 'The subject of the welcome register message.';
    }

    public function tokenConfirmWithinHelp(): string
    {
        return 'The time in seconds before a confirmation token becomes invalid. ' . "\n" .
            'After expiring this time user have to request new confirmation token on special page.';
    }

    public function tokenRecoverWithinHelp(): string
    {
        return 'The time in seconds before a recovery token becomes invalid.  ' . "\n" .
            'After expiring this time user have to request new recovery message.';
    }

    public function rules(): array
    {
        return [
            'confirmation' => [
                new Required()
            ]
        ];
    }
}
