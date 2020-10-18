<?php

declare(strict_types=1);

namespace App\Module\Rbac\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Required;

final class ItemForm extends FormModel
{
    private string $name = '';
    private string $description = '';
    private string $type = '';

    public function attributeLabels(): array
    {
        return [];
    }

    public function formName(): string
    {
        return 'Item';
    }

    public function rules(): array
    {
        return [
            'name' => [new Required()],
            'description' => [new Required()],
            'type' => [
                (new Required()), static function ($value): Result {
                    $result = new Result();

                    if ($value === '0') {
                        $result->addError('You must select a type item.');
                    }

                    return $result;
                }
            ]
        ];
    }
}
