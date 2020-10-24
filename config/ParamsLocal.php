<?php

declare(strict_types=1);

namespace Yii;

use Psr\Log\LogLevel;
use Yiisoft\Form\Widget\Field;

use function dirname;

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
    'appConfig' => [
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
    'cachePath' => dirname(__DIR__) . '/runtime/cache',
    'composerView' => dirname(__DIR__) . '/resources/mail',
    'sqliteDsn' => 'sqlite:' . dirname(__DIR__) . '/resources/database/yiitest.sq3',
    'fieldConfig' => [
        'labelOptions' => [['label' => '']],
        'inputOptions' => [['class' => 'field input']],
        'errorOptions' => [['class' => 'has-text-left has-text-danger is-italic']]
    ],
    'fileMailerStorage' => dirname(__DIR__) . '/runtime/mail',
    'fileRotatorMaxFiles' => 5,
    'fileRotatorMaxFileSize' => 10,
    'htmlRendererConfig' => [
        'default' => [
            'callStackItem',
            'error',
            'exception',
            'previousException'
        ],
        'path' => dirname(__DIR__) . '/vendor/yiisoft/yii-web/src/ErrorHandler/templates',
    ],
    'logFile' => dirname(__DIR__) . '/runtime/logs/app.log',
    'loginUrl' => '/auth/login',
    'logLevels' => [
        LogLevel::EMERGENCY,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::INFO,
        LogLevel::DEBUG
    ],
    'mailerConfig' =>  [
        'host' => 'smtp.example.com',
        'port' => 25,
        'encryption' => null,
        'username' => '',
        'password' => ''
    ],
    'themePathMap' => [
        '@layout' => '@AdminOneLayout',
    ],
    'writeToFiles' => true
];
