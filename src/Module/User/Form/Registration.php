<?php

declare(strict_types=1);

namespace App\Module\User\Form;

use App\Service\Parameters;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\ValidatorFactoryInterface;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\MatchRegularExpression;

final class Registration extends FormModel
{
    private string $email = '';
    private string $username = '';
    private string $password = '';
    private string $ip = '';
    private Parameters $app;

    public function __construct(Parameters $app, ValidatorFactoryInterface $validator)
    {
        $this->app = $app;

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
                new MatchRegularExpression($this->app->get('user.usernameRegExp'))
            ],
            'password' => $this->passwordRules()
        ];
    }

    private function passwordRules(): array
    {
        $result = [];

        if ($this->app->get('user.generatingPassword') === false) {
            $result = [
                new Required(),
                (new HasLength())->min(6)->max(72)->tooShortMessage('Password should contain at least 6 characters.')
            ];
        }

        return $result;
    }
}
