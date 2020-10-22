<?php

declare(strict_types=1);

namespace Yii\Component;

use Yiisoft\Rbac\Manager;
use Yiisoft\Rbac\Php\Storage;
use Yiisoft\Rbac\RuleFactory\ClassNameRuleFactory;
use Yiisoft\Rbac\RuleFactoryInterface;
use Yiisoft\Rbac\StorageInterface;

return [
    StorageInterface::class => [
        '__class' => Storage::class,
        '__construct()' => [
            'directory' => $params['aliases']['@root'] . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'rbac'
        ]
    ],
    RuleFactoryInterface::class => ClassNameRuleFactory::class,
    AccessCheckerInterface::class => Manager::class,
];
