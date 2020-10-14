<?php

declare(strict_types=1);

namespace Yii\Component;

use Yii\Params;
use Yiisoft\Form\Widget\Field;

$params = new Params();

return [
    Field::class => fn() => Field::widget($params->getFieldconfig())
];
