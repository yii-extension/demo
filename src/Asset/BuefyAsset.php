<?php

declare(strict_types=1);

namespace App\Asset;

use App\Theme\AdminOneBulma\Asset\MaterialDesignIconsAsset;
use Yiisoft\Assets\AssetBundle;

final class BuefyAsset extends AssetBundle
{
    public ?string $basePath = '@assets';
    public ?string $baseUrl = '@assetsUrl';
    public ?string $sourcePath = '@npm/buefy';

    public array $css = [
        'dist/buefy.css',
    ];

    public array $js = [
        'dist/buefy.js',
    ];

    public array $depends = [
        MaterialDesignIconsAsset::class,
        VueAsset::class
    ];
}
