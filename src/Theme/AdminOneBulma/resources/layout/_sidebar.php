<?php

declare(strict_types=1);

use App\Service\ParameterService;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\Yii\Bulma\Menu;

/**
 * @var ParameterService $app
 * @var string|null $id
 * @var UrlGeneratorInterface $url
 * @var UrlMatcherInterface $urlMatcher
 */

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
        'label' => 'Dashboard'
    ],
    [
        'label' => 'Modules',
        'items' => [
            [
                'label' => 'Users',
                'items' => [
                    [
                        'label' => 'Manage',
                        'url' => '/admin/index',
                        'icon' => 'mdi mdi-account-circle',
                        'iconOptions' => ['class' => 'icon']
                    ],
                    [
                        'label' => 'Create',
                        'url' => $url->generate('admin/create'),
                        'options' => ['class' => 'ml-5'],
                        'linkOptions' => ['class' => 'pl-3'],
                        'visible' => in_array(
                            $currentRoute,
                            [
                                'index',
                                'admin/index',
                                'item/index',
                                'item/create',
                                'item/edit',
                                'settings/index',
                                'settings/mailer'
                            ]
                        ) ? false : true
                    ],
                    [
                        'label' => 'Edit',
                        'url' => $id !== null ? $url->generate('admin/edit', ['id' => $id]) : '#',
                        'options' => ['class' => 'ml-5'],
                        'linkOptions' => ['class' => 'pl-3'],
                        'visible' => in_array(
                            $currentRoute,
                            [
                                'index',
                                'admin/index',
                                'admin/create',
                                'item/index',
                                'item/create',
                                'item/edit',
                                'settings/index',
                                'settings/mailer'
                            ]
                        ) ? false : true
                    ],
                    [
                        'label' => 'Information',
                        'url' => $id !== null ? $url->generate('admin/info', ['id' => $id]) : '#',
                        'options' => ['class' => 'ml-5'],
                        'linkOptions' => ['class' => 'pl-3'],
                        'visible' => in_array(
                            $currentRoute,
                            [
                                'index',
                                'admin/index',
                                'admin/create',
                                'item/index',
                                'item/create',
                                'item/edit',
                                'settings/index',
                                'settings/mailer'
                            ]
                        ) ? false : true
                    ],
                ]
            ],
            [
                'label' => 'Rbac',
                'items' => [
                    [
                        'label' => 'Items',
                        'items' => [
                            [
                                'label' => 'Manage',
                                'url' => '/item/index',
                                'icon' => 'mdi mdi-view-list-outline',
                                'iconOptions' => ['class' => 'icon']
                            ],
                            [
                                'label' => 'Create',
                                'url' => $url->generate('item/create'),
                                'options' => ['class' => 'ml-5'],
                                'linkOptions' => ['class' => 'pl-3'],
                                'visible' => in_array(
                                    $currentRoute,
                                    [
                                        'index',
                                        'admin/index',
                                        'admin/create',
                                        'admin/edit',
                                        'admin/info',
                                        'item/index',
                                        'settings/index',
                                        'settings/mailer'
                                    ]
                                ) ? false : true
                            ],
                            [
                                'label' => 'Edit',
                                'url' => $id !== null ? $url->generate('item/edit', ['id' => $id]) : '#',
                                'options' => ['class' => 'ml-5'],
                                'linkOptions' => ['class' => 'pl-3'],
                                'visible' => in_array(
                                    $currentRoute,
                                    [
                                        'index',
                                        'admin/index',
                                        'admin/create',
                                        'admin/edit',
                                        'admin/info',
                                        'item/index',
                                        'item/create',
                                        'settings/index',
                                        'settings/mailer'
                                    ]
                                ) ? false : true
                            ],
                        ]
                    ],
                    [
                        'label' => 'Rules'
                    ]
                ]
            ],
            [
                'label' => 'Settings',
                'items' => [
                    [
                        'label' => 'Module User',
                        'items' => [
                            [
                                'label' => 'General',
                                'url' => '/settings/index',
                                'icon' => 'mdi mdi-account-circle',
                                'iconOptions' => ['class' => 'icon']
                            ],
                            [
                                'label' => 'Mailer',
                                'url' => '/settings/mailer',
                                'icon' => 'mdi mdi-account-circle',
                                'iconOptions' => ['class' => 'icon']
                            ],
                        ]
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
    ->options(['class' => 'aside is-placed-left is-expanded'])
    ->subMenuTemplate("<ul class='menu-list ml-2'>\n{items}\n</ul>");
