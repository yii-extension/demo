<?php

declare(strict_types=1);

namespace App\Module\User\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\Required;

final class Resend extends FormModel
{
    private string $email = '';

    public function attributeLabels(): array
    {
        return [
            'email' => 'Email'
        ];
    }

    public function rules(): array
    {
        return [
            'email' => [
                new Required(),
                new Email()
            ]
        ];
    }
}
