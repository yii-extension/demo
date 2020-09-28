<?php

declare(strict_types=1);

namespace Yii\Component;

use App\Service\Parameters;
use App\Module\User\Config\ParamsModuleUser;
use App\Theme\AdminOneBulma\Config\ParamsTheme;
use Yii\Params;

$params = new Params();
$paramsTheme = new ParamsTheme();
$paramsUser = new ParamsModuleUser();

return [
    /** component parameters */
    Parameters::class  => [
        '__class' => Parameters::class,
        '__construct()' => [
            array_merge_recursive(
                $params->getApplicationConfig(),
                $paramsTheme->getParams(),
                $paramsUser->getParams()
            )
        ]
    ]
];
