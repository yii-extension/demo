<?php

declare(strict_types=1);

namespace App\Theme\AdminOneBulma\Asset;

use App\Asset\AppAsset;
use Yii\Extension\Fontawesome\Cdn\Css\CdnAllAsset;
use Yiisoft\Assets\AssetBundle;
use Yiisoft\Yii\Bulma\Asset\MessagePluginAsset;

final class AdminOneAsset extends AssetBundle
{
    public ?string $basePath = '@assets';
    public ?string $baseUrl = '@assetsUrl';
    public ?string $sourcePath = '@AdminOneAssets';

    public array $css = [
        'css/main.css'
    ];

    public array $js = [
        'js/main.js'
    ];

    public array $publishOptions = [
        'only' => [
            'css/main.css',
            'js/main.js',
        ],
    ];

    public array $depends = [
        AppAsset::class,
        CdnAllAsset::class,
        MaterialDesignIconsAsset::class,
        MessagePluginAsset::class
    ];
}
