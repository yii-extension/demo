<?php

declare(strict_types=1);

namespace Yii\Component;

use Swift_SmtpTransport;
use Swift_Plugins_LoggerPlugin;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Mailer\Composer;
use Yiisoft\Mailer\FileMailer;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageFactory;
use Yiisoft\Mailer\MessageFactoryInterface;
use Yiisoft\Mailer\SwiftMailer\Logger;
use Yiisoft\Mailer\SwiftMailer\Mailer;
use Yiisoft\Mailer\SwiftMailer\Message;
use Yiisoft\View\WebView;

return [
    /** component mailer */
    Composer::class => [
        '__class' => Composer::class,
        '__construct()' => [
            Reference::to(WebView::class),
            $params['composerView']
        ]
    ],

    MessageFactory::class => [
        '__class' => MessageFactory::class,
        '__construct()' => [
            Message::class
        ]
    ],

    MessageFactoryInterface::class => MessageFactory::class,

    Logger::class => [
        '__class' => Logger::class,
        '__construct()' => [Reference::to(LoggerInterface::class)]
    ],

    Swift_Plugins_LoggerPlugin::class => [
        '__class' => Swift_Plugins_LoggerPlugin::class,
        '__construct()' => [Reference::to(Logger::class)]
    ],

    Mailer::class => [
        '__class' => Mailer::class,
        '__construct()' => [
            Reference::to(MessageFactoryInterface::class),
            Reference::to(Composer::class),
            Reference::to(EventDispatcherInterface::class),
            Reference::to(LoggerInterface::class),
            Reference::to(Swift_SmtpTransport::class)
        ],
        'registerPlugin()' => [Reference::to(Swift_Plugins_LoggerPlugin::class)]
    ],

    FileMailer::class => [
        '__class' => FileMailer::class,
        '__construct()' => [
            Reference::to(MessageFactoryInterface::class),
            Reference::to(Composer::class),
            Reference::to(EventDispatcherInterface::class),
            Reference::to(LoggerInterface::class),
            $params['fileMailerStorage']
        ]
    ],

    MailerInterface::class => static function (ContainerInterface $container) use ($params) {
        $writeToFiles = true;

        if ($writeToFiles) {
            return $container->get(FileMailer::class);
        }

        return $container->get(Mailer::class);
    }
];
