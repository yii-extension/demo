<?php

declare(strict_types=1);

namespace App\Module\User\Form;

use App\Module\User\Repository\ModuleSettings;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\ValidatorFactoryInterface;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\MatchRegularExpression;

final class Register extends FormModel
{
    private string $email = '';
    private string $username = '';
    private string $password = '';
    private string $ip = '';
    private ModuleSettings $settings;

    public function __construct(ModuleSettings $settings, ValidatorFactoryInterface $validator)
    {
        $this->settings = $settings;

        parent::__construct($validator);
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'Email:',
            'username' => 'Username:',
            'password' => 'Password:'
        ];
    }

    public function formName(): string
    {
        return 'Register';
    }

    public function ip(string $value): void
    {
        $this->ip = $value;
    }

    public function rules(): array
    {
        return [
            'email' => [
                new Required(),
                new Email()
            ],
            'username' => [
                new Required(),
                (new HasLength())->min(3)->max(255)->tooShortMessage('Username should contain at least 3 characters.'),
                new MatchRegularExpression($this->settings->getUsernameRegExp())
            ],
            'password' => $this->passwordRules()
        ];
    }

    private function passwordRules(): array
    {
        $result = [];

        if ($this->settings->isGeneratingPassword() === false) {
            $result = [
                new Required(),
                (new HasLength())->min(6)->max(72)->tooShortMessage('Password should contain at least 6 characters.')
            ];
        }

        return $result;
    }
}
