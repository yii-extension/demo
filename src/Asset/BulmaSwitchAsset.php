<?php

declare(strict_types=1);

namespace App\Asset;

use Yiisoft\Assets\AssetBundle;
use Yiisoft\Yii\Bulma\Asset\BulmaAsset;

final class BulmaSwitchAsset extends AssetBundle
{
    public ?string $basePath = '@assets';
    public ?string $baseUrl = '@assetsUrl';
    public ?string $sourcePath = '@npm/bulma-switch/dist/css';

    public array $css = [
        'bulma-switch.min.css'
    ];

    public array $depends = [
        BulmaAsset::class
    ];
}
