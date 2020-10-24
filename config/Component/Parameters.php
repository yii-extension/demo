<?php

declare(strict_types=1);

namespace Yii\Component;

use App\Service\ParameterService;

return [
    /** component parameters */
    ParameterService::class  => [
        '__class' => ParameterService::class,
        '__construct()' => [
            $params['appConfig']
        ]
    ]
];
