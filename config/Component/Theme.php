<?php

declare(strict_types=1);

namespace Yii\App\Provider;

use Psr\Container\ContainerInterface;
use Yii\Params;
use Yiisoft\Aliases\Aliases;
use Yiisoft\View\Theme;

return [
    /** component theme */
    Theme::class => static function (ContainerInterface $container) {
        $aliases = $container->get(Aliases::class);
        $map = ['@layout' => '@AdminOneLayout'];
        $pathMap = [
        ];

        foreach ($map as $key => $value) {
            $pathMap = [
                $aliases->get($key) => $aliases->get($value)
            ];
        }

        return new Theme($pathMap);
    }
];
