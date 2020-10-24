<?php

declare(strict_types=1);

namespace Yii;

use Yiisoft\Composer\Config\Builder;

use function array_merge;

$params = require Builder::path('params-local');

return array_merge(
    require(__DIR__ . '/Component/DiContainer.php'),
    require(__DIR__ . '/Component/Aliases.php'),
    require(__DIR__ . '/Component/EventDispatcher.php'),
    require(__DIR__ . '/Component/LogTargetFile.php'),
    require(__DIR__ . '/Component/Cache.php'),
    require(__DIR__ . '/Component/Profiler.php'),
    require(__DIR__ . '/Component/WebViewConsole.php'),
    require(__DIR__ . '/Component/Db.php'),
    require(__DIR__ . '/Component/Migration.php')
);
