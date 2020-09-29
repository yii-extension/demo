<?php

declare(strict_types=1);

namespace App\Module\User\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Required;

final class Reset extends FormModel
{
    private string $password = '';

    public function attributeLabels(): array
    {
        return [
            'password' => 'Password:'
        ];
    }

    public function formName(): string
    {
        return 'Reset';
    }

    public function rules(): array
    {
        return [
            'password' => [
                new Required(),
                (new HasLength())->min(6)->max(72)->tooShortMessage('Password should contain at least 6 characters.')
            ],
        ];
    }
}
