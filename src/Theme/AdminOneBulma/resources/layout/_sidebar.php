<?php

declare(strict_types=1);

/**
 * @var App\Service\Parameters $app
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var Yiisoft\Router\UrlMatcherInterface $urlMatcher
 */

use Yiisoft\Yii\Bulma\Menu;

$brand = <<<HTML
<div class = "aside-tools">
    <div class=aside-tools-label>
        <div class = "navbar-brand">
            <span class="navbar-item">
                <img src="/images/yii-logo-sidebar.png" alt="">
            </span>
            <a style = "color:white" href="/">My Project</a>
        </div>
    </div>
</div>
HTML;

$items = [
    [
        'label' => 'General',
        'items' => [
            [
                'label' => 'Dashboard',
                'url' => '/',
                'icon' => 'mdi mdi-desktop-mac',
                'iconOptions' => ['class' => 'icon']
            ]
        ]
    ]
];
?>

<?= Menu::widget()
    ->brand($brand)
    ->currentPath($url->generate($urlMatcher->getCurrentRoute()->getName()))
    ->items($items)
    ->options(['class' => 'aside is-placed-left is-expanded']);
