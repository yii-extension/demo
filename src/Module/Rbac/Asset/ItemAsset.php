<?php

declare(strict_types=1);

namespace App\Module\Rbac\Asset;

use Yiisoft\Assets\AssetBundle;
use Yiisoft\Yii\Bulma\Asset\BulmaAsset;

final class ItemAsset extends AssetBundle
{
    public ?string $basePath = '@assets';
    public ?string $baseUrl = '@assetsUrl';
    public ?string $sourcePath = '@rbac/resources/asset';

    public array $css = [
        'css/item.css',
    ];

    public array $js = [
        'vue/item_datatable.js'
    ];

    public array $depends = [
        BulmaAsset::class
    ];
}
