<?php

declare(strict_types=1);

namespace Yii\App\Provider;

use Psr\Container\ContainerInterface;
use Yii\Params;
use Yiisoft\Aliases\Aliases;
use Yiisoft\View\Theme;

$params = new Params();

return [
    Theme::class => static function (ContainerInterface $container) use ($params) {
        $aliases = $container->get(Aliases::class);
        $pathMap = [];

        foreach ($params->getThemePathMap() as $key => $value) {
            $pathMap = [
                $aliases->get($key) => $aliases->get($value)
            ];
        }

        return new Theme($pathMap);
    }
];
