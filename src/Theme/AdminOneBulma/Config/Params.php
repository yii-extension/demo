<?php

declare(strict_types=1);

namespace App\Theme\AdminOneBulma\Config;

final class Params
{
    public function getParams(): array
    {
        $htmlLabel = <<<HTML
            <div class = 'is-user-avatar'>
                {logo}
            </div>
            <div class = 'is-user-name'>
                <span>{username}</span>
            </div>
            <span class= 'icon'></span>
        HTML;

        return [
            'app' => [
                'nav' => [
                    'guest' => [
                        ['label' => 'Register', 'url' => '/registration/register'],
                        ['label' => 'Login', 'url' => '/auth/login'],
                    ],
                    'logged' => [
                        [
                            'label' => $htmlLabel,
                            'items' => [
                                [
                                    'label' => 'Logout',
                                    'url' => '/auth/logout',
                                    'icon' => 'mdi mdi-logout',
                                    'iconOptions' => 'icon'
                                ]
                            ],
                            'options' => ['class' => 'has-dropdown-with-icons has-divider has-user-avatar'],
                            'encode' => false
                        ]
                    ]
                ]
            ]
        ];
    }
}
