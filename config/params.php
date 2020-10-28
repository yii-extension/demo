<?php

declare(strict_types=1);

use App\Command\HelloCommand;
use Psr\Log\LogLevel;
use Yiisoft\Form\Widget\Field;

return [
    'aliases' => [
        '@root' => dirname(__DIR__),
        '@assets' => '@root/public/assets',
        '@assetsUrl' => '/assets',
        '@mail' => '@root/resources/mail',
        '@npm' => '@root/node_modules',
        '@resources' => '@root/resources',
        '@runtime' => '@root/runtime',
        '@vendor' => '@root/vendor',
        '@layout' => '@root/resources/views/layout',
        '@views' => '@root/resources/views',

        /** config theme adminonebulma */
        '@AdminOneLayout' => '@root/src/Theme/AdminOneBulma/resources/layout',

        /** config yii-db-migration */
        '@yiisoft/yii/db/migration' => '@vendor/yiisoft/yii-db-migration',

        /** config module-user */
        '@user' => '@root/src/Module/User',

        /** config module-user */
        '@rbac' => '@root/src/Module/Rbac'
    ],

    'app' => [
        'charset' => 'UTF-8',
        'emailFrom' => 'tester@example.com',
        'language' => 'en',
        'logo' => '/images/yii-logo.jpg',
        'mailer' => [
            'from' => 'tester@example.com'
        ],

        /** config widget nav */
        'nav' => [
            /**
            * Example menu config simple link, dropdown menu.
            *[
            *   'label' => 'Home',
            *   'url' => ['site/index']
            *],
            *[
            *   'label' => 'Dropdown',
            *   'items' => [
            *       ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
            *       ['label' => 'Level 2 - Dropdown A', 'url' => '#'],
            *       '<li class="dropdown-divider"></li>',
            *       '<li style="color:black;list-style-type: none">Dropdown Header</li>',
            *       ['label' => 'Level 3 - Dropdown B', 'url' => '#'],
            *       ['label' => 'Level 4 - Dropdown A', 'url' => '#'],
            *   ],
            *],
            */
            'guest' => [
                ['label' => 'About', 'url' => '/about'],
                ['label' => 'Contact', 'url' => '/contact']
            ],
            'logged' => [],
        ],

        /** config widget navBar */
        'navBar' => [
            'config' => [
                'brandLabel()' => ['My Project'],
                'brandImage()' => ['/images/yii-logo.jpg'],
                'itemsOptions()' => [['class' => 'navbar-end']],
                'options()' => [['class' => 'is-black', 'data-sticky' => '', 'data-sticky-shadow' => '']]                    ],
        ],

        'name' => 'My Project'
    ],

    'yiisoft/db-sqlite' => [
        'dsn' => 'sqlite:' . dirname(__DIR__) . '/resources/database/yiitest.sq3'
    ],

    'yiisoft/log-target-file' => [
        'fileTarget' => [
            'file' => '@runtime/logs/app.txt',
            'levels' => [
                LogLevel::EMERGENCY,
                LogLevel::ERROR,
                LogLevel::WARNING,
                LogLevel::INFO,
                LogLevel::DEBUG,
            ],
            'dirMode' => 0755,
            'fileMode' => null
        ],
        'file-rotator' => [
            'maxFileSize' => 10,
            'maxFiles' => 5,
            'fileMode' => null,
            'rotateByCopy' => null
        ]
    ],

    'yiisoft/mailer' => [
        'composer' => [
            'composerView' => '@resources/mail'
        ],
        'fileMailer' => [
            'fileMailerStorage' => '@runtime/mail'
        ],
        'writeToFiles' => true
    ],
    'swiftmailer/swiftmailer' => [
        'SwiftSmtpTransport' => [
            'host' => 'smtp.example.com',
            'port' => 25,
            'encryption' => null,
            'username' => 'admin@example.com',
            'password' => ''
        ]
    ],

    'yiisoft/view' => [
        'theme' => [
            'pathMap' => [
                '@layout' => '@AdminOneLayout'
            ],
            'basePath' => '',
            'baseUrl' => '',
        ]
    ],

    'yiisoft/yii-console' => [
        'commands' => [
            'hello' => HelloCommand::class,
        ]
    ],

    'yiisoft/yii-db-migration' => [
        'createNameSpace' => 'App\\Migration',
        'createPath' => '',
        'updateNameSpace' => [
            'App\\Module\\Rbac\\Migration',
            'App\\Module\\User\\Migration'
        ],
        'updatePath' => []
    ],

    'yiisoft/yii-web' => [
        'userAuth' => [
            'authUrl' => '/auth/login'
        ]
    ]
];
