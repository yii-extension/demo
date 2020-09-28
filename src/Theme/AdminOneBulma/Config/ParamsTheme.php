<?php

declare(strict_types=1);

namespace App\Theme\AdminOneBulma\Config;

final class ParamsTheme
{
    public function getParams(): array
    {
        return [
            'app' => [
                'breadcrumbs' => [
                    'homeLink' => [
                        'label' => 'Home',
                        'url' => '/',
                        'icon' => 'fas fa-home',
                        'iconOptions' => 'icon',
                        'encode' => false
                    ],
                    'options' => [
                        'class' => 'has-succeeds-separator is-centered has-margin-top-20'
                    ]
                ],
                'menu' => [
                    'brand' =>
                        '<div class = "aside-tools">
                            <div class=aside-tools-label>
                                <div class = "navbar-brand">
                                    <span class="navbar-item">
                                        <img src="/images/yii-logo-sidebar.png" alt="">
                                    </span>
                                    <a style = "color:white" href="/">My Project</a>
                                </div>
                            </div>
                        </div>',
                    'items' => [
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
                    ]
                ],
                'nav' => [
                    'guest' => [
                        ['label' => 'Register', 'url' => '/registration/register'],
                        ['label' => 'Login', 'url' => '/auth/login'],
                    ],
                    'logged' => [
                        [
                            'label' => '<div class = "is-user-avatar">{logo}</div>' .
                                       '<div class = "is-user-name"><span>{username}</span></div><span class="icon"></span>',
                            'items' => [
                                [
                                    'label' => 'Logout',
                                    'url' => '/auth/logout',
                                    'icon' => 'mdi mdi-logout',
                                    'iconOptions' => 'icon'
                                ]
                            ],
                            'options' => ['class' => 'has-dropdown-with-icons has-divider has-user-avatar'],
                            'encode' => false,
                        ]
                    ]
                ],
                'navBar' => [
                    'config' => [
                        'itemsOptions()' => [
                            ['class' => 'navbar-end']
                        ],
                        'options()' => [
                            ['class' => 'navbar is-fixed-top is-black']
                        ],
                    ],
                    'brand' =>
                        '<div class="navbar-brand">
                            <a class="navbar-item is-hidden-desktop jb-aside-mobile-toggle">
                                <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
                            </a>
                        </div>
                        <div class="navbar-brand is-right">
                            <a class="navbar-item is-hidden-desktop jb-navbar-menu-toggle" data-target="w1-navbar-Menu">
                                <span class="icon"><i class="mdi mdi-dots-vertical"></i></span>
                            </a>
                        </div>'
                ]
            ]
        ];
    }
}
