<?php

declare(strict_types=1);

namespace App\Module\User\Config;

final class Params
{
    public function getParams(): array
    {
        return [
            'user' => [
                /**
                 * If this option is set to true, module sends email that contains a confirmation link that user must
                 * click to complete registration.
                 */
                'confirmation' => false,

                /**
                 * If this option is set to true, password field on registration page will be hidden and password for
                 * user will be generated automatically.
                 */
                'generatingPassword' => false,

                /** Configure the sending email. */
                'emailFrom' => 'support@example.com',

                /** Set message header flash message. */
                'messageHeader' => 'System Notification - Yii Demo User Module AR.',

                /** If this option is to true, users will be able to recovery their forgotten passwords. */
                'passwordRecovery' => true,

                /** Registration enabled/disabled module user. */
                'register' => true,

                /** Configure the subject confirm email. */
                'subjectConfirm' => 'Confirm account.',

                /** Configure the subject password email. */
                'subjectPassword' => 'Your password has been changed.',

                /** Configure the subject re-confirmation email. */
                'subjectReconfirmation' => 'Confirm email change.',

                /** Configure the subject recovery email. */
                'subjectRecovery' => 'Complete password reset.',

                /** Configure the subject welcome email. */
                'subjectWelcome' => 'Welcome.',

                /** The time before a confirmation token becomes invalid. */
                'tokenConfirmWithin' => 86400,

                /** The time before a recovery token becomes invalid. */
                'tokenRecoverWithin' => 86400,

                /** Default username regexp */
                'usernameRegExp' => '/^[-a-zA-Z0-9_\.@]+$/',

                /** config widget $field */
                'field' => [
                    'labelOptions()' => [['label' => '']],
                    'inputOptions()' => [['class' => 'field input']],
                    'errorOptions()' => [['class' => 'has-text-left has-text-danger is-italic']]
                ]
            ]
        ];
    }
}
