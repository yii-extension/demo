<?php

declare(strict_types=1);

namespace Yii\Component;

use Yiisoft\Yii\Web\ErrorHandler\HtmlRenderer;
use Yiisoft\Yii\Web\ErrorHandler\ThrowableRendererInterface;

return [
    /** component error exception yii-web */
    ThrowableRendererInterface::class => HtmlRenderer::class,

    HtmlRenderer::class => [
        '__class' => HtmlRenderer::class,
        '__construct()' => [$params['htmlRendererConfig']]
    ],
];
