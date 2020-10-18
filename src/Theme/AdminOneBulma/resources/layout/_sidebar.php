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

$currentRoute = $urlMatcher->getCurrentRoute()->getName();
$route = $id !== null ? $url->generate($currentRoute, ['id' => $id]) : $url->generate($currentRoute);

$items = [
    [
        'label' => 'Dashboard',
        'items' => [
            [
                'label' => 'Users',
                'items' => [
                    [
                        'label' => 'Manage Users',
                        'url' => '/admin/index',
                        'icon' => 'mdi mdi-account-circle',
                        'iconOptions' => ['class' => 'icon']
                    ],
                    [
                        'label' => 'Information',
                        'url' => $id !== null ? $url->generate('admin/info', ['id' => $id]) : '#',
                        'options' => ['class' => 'pl-6'],
                        'visible' => in_array($currentRoute, ['admin/index', 'item/index', 'item/create', 'item/edit'])
                            ? false
                            : true
                    ],
                ]
            ],
            [
                'label' => 'Rbac',
                'items' => [
                    [
                        'label' => 'Manage Item',
                        'url' => '/item/index',
                        'icon' => 'mdi mdi-view-list-outline',
                        'iconOptions' => ['class' => 'icon']
                    ],
                    [
                        'label' => 'Create',
                        'url' => $url->generate('item/create'),
                        'options' => ['class' => 'pl-6'],
                        'visible' => in_array($currentRoute, ['admin/index', 'item/index', 'admin/info']) ? false : true
                    ],
                    [
                        'label' => 'Edit',
                        'url' => $id !== null ? $url->generate('item/edit', ['id' => $id]) : '#',
                        'options' => ['class' => 'pl-6'],
                        'visible' => in_array($currentRoute, ['admin/index', 'item/index', 'item/create', 'admin/info'])
                            ? false
                            : true
                    ],
                    [
                        'label' => 'Rule',
                        'url' => '#',
                        'icon' => 'mdi mdi-account-check',
                        'iconOptions' => ['class' => 'icon']
                    ]
                ]
            ],
            [
                'label' => 'Resources',
                'items' => [
                    [
                        'label' => 'Settings',
                        'url' => '#',
                        'icon' => 'mdi mdi-cogs',
                        'iconOptions' => ['class' => 'icon']
                    ]
                ]
            ]
        ]
    ]
];
?>

<?= Menu::widget()
    ->brand($brand)
    ->currentPath($route)
    ->encodeLabels(false)
    ->items($items)
    ->options(['class' => 'aside is-placed-left is-expanded']);
