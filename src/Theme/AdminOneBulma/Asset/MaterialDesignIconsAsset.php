<?php

declare(strict_types=1);

namespace App\Theme\AdminOneBulma\Asset;

use Yiisoft\Assets\AssetBundle;

final class MaterialDesignIconsAsset extends AssetBundle
{
    public bool $cdn = true;

    public array $css = [
        'https://cdn.materialdesignicons.com/5.3.45/css/materialdesignicons.css'
    ];
}
