<?php

declare(strict_types=1);

namespace App\Module\User\Asset;

use Yiisoft\Assets\AssetBundle;
use Yiisoft\Yii\Bulma\Asset\BulmaAsset;

final class AdminCreateAsset extends AssetBundle
{
    public ?string $basePath = '@assets';
    public ?string $baseUrl = '@assetsUrl';
    public ?string $sourcePath = '@user/resources/asset';

    public array $css = [
        'css/admincreate.css',
    ];

    public array $depends = [
        BulmaAsset::class
    ];
}
