<?php

declare(strict_types=1);

namespace Yii\Component;

use Psr\Log\LoggerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Assets\AssetConverter;
use Yiisoft\Assets\AssetConverterInterface;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Assets\AssetPublisher;
use Yiisoft\Assets\AssetPublisherInterface;
use Yiisoft\Factory\Definitions\Reference;

return [
    /** component assets */
    AssetConverterInterface::class => [
        '__class' => AssetConverter::class,
        '__construct()' => [
            Reference::to(Aliases::class),
            Reference::to(LoggerInterface::class)
        ]
    ],

    AssetPublisherInterface::class => [
        '__class' => AssetPublisher::class,
        '__construct()' => [
            Reference::to(Aliases::class)
        ]
    ],

    AssetManager::class => [
        '__class' => AssetManager::class,
        '__construct()' => [
            Reference::to(LoggerInterface::class)
        ],
        'setConverter()' => [Reference::to(AssetConverterInterface::class)],
        'setPublisher()' => [Reference::to(AssetPublisherInterface::class)],
    ]
];
