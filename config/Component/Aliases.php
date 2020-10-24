<?php

declare(strict_types=1);

namespace Yii\Component;

use Yii\Params;
use Yiisoft\Aliases\Aliases;

return [
    /** component aliases */
    Aliases::class => [
        '__class' => Aliases::class,
        '__construct()' => [$params['aliases']]
    ],
];
