<?php

declare(strict_types=1);

namespace Yii\Component;

use Psr\Log\LoggerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\View\Theme;
use Yiisoft\View\WebView;

return [
    /** component view */
    WebView::class => [
        '__class' => WebView::class,
        '__construct()' => [
            $params['viewPath'],
            Reference::to(Theme::class),
            Reference::to(EventDispatcherInterface::class),
            Reference::to(LoggerInterface::class)
        ]
    ],
];
