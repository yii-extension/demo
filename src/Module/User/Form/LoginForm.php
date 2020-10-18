<?php

declare(strict_types=1);

namespace App\Module\User\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;

final class LoginForm extends FormModel
{
    private string $login = '';
    private string $password = '';

    public function attributeLabels(): array
    {
        return [
            'login' => 'Login',
            'password' => 'Password'
        ];
    }

    public function formName(): string
    {
        return 'Login';
    }

    public function rules(): array
    {
        return [
            'login' => [new Required()],
            'password' => [new Required()]
        ];
    }
}
