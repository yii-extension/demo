<?php

declare(strict_types=1);

use App\Service\ParameterService;

return [
    /** component parameters */
    ParameterService::class  => [
        '__class' => ParameterService::class,
        '__construct()' => [
            $params['app']
        ]
    ]
];
