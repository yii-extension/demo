<?php

declare(strict_types=1);

namespace Yii\Component;

use Yiisoft\Form\Widget\Field;

return [
    /** component widget field */
    Field::class => static function () {
        $fieldConfig = [
            'labelOptions()' => [['label' => '']],
            'inputOptions()' => [['class' => 'field input']],
            'errorOptions()' => [['class' => 'has-text-left has-text-danger is-italic']]
        ];

        return Field::Widget($fieldConfig);
    }
];
