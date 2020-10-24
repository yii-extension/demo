<?php

declare(strict_types=1);

namespace Yii\Component;

use Psr\Log\LoggerInterface;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Log\Logger;
use Yiisoft\Log\Target\File\FileRotator;
use Yiisoft\Log\Target\File\FileRotatorInterface;
use Yiisoft\Log\Target\File\FileTarget;

return [
    /** component logger - target file */
    FileRotatorInterface::class => [
        '__class' => FileRotator::class,
        '__construct()' => [
            $params['fileRotatorMaxFileSize'],
            $params['fileRotatorMaxFiles'],
            null,
            null
        ]
    ],

    FileTarget::class => [
        '__class' => FileTarget::class,
        '__construct()' => [
            $params['logFile'],
            Reference::to(FileRotatorInterface::class)
        ],
        'setLevels()' => [$params['logLevels']]
    ],

    LoggerInterface::class => [
        '__class' => Logger::class,
        '__construct()' => [
            ['file' => Reference::to(FileTarget::class)]
        ],
    ],
];
