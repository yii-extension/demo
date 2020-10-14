<?php

declare(strict_types=1);

namespace Yii\Component;

use App\Service\Parameters;
use App\Module\User\Config\Params as UserParams;
use App\Theme\AdminOneBulma\Config\Params as ThemeParams;
use Yii\Params;

$params = new Params();
$themeParams = new ThemeParams();

return [
    /** component parameters */
    Parameters::class  => [
        '__class' => Parameters::class,
        '__construct()' => [
            array_merge_recursive(
                $params->getApplicationConfig(),
                $themeParams->getParams()
            )
        ]
    ]
];
