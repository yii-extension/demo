<?php

declare(strict_types=1);

namespace Yii\Component;

use Swift_SmtpTransport;
use Swift_Plugins_LoggerPlugin;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Yii\Params;
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

$params = new Params();

return [
    /** component mailer */
    Composer::class => [
        '__class' => Composer::class,
        '__construct()' => [
            Reference::to(WebView::class),
            $params->getComposerView()
        ]
    ],

    MessageFactory::class => [
        '__class' => MessageFactory::class,
        '__construct()' => [
            Message::class
        ]
    ],

    MessageFactoryInterface::class => MessageFactory::class,

    Mailer::class => [
        '__class' => Mailer::class,
        '__construct()' => [
            Reference::to(MessageFactoryInterface::class),
            Reference::to(Composer::class),
            Reference::to(EventDispatcherInterface::class),
            Reference::to(LoggerInterface::class),
            Reference::to(Swift_Transport::class)
        ]
    ],

    FileMailer::class => [
        '__class' => FileMailer::class,
        '__construct()' => [
            Reference::to(MessageFactoryInterface::class),
            Reference::to(Composer::class),
            Reference::to(EventDispatcherInterface::class),
            Reference::to(LoggerInterface::class),
            $params->getFileMailerStorage()
        ]
    ],

    MailerInterface::class => static function (ContainerInterface $container) use ($params) {
        if ($params->writetoFiles()) {
            return $container->get(FileMailer::class);
        }

        $mailer = new Mailer(
            $container->get(MessageFactoryInterface::class),
            $container->get(Composer::class),
            $container->get(EventDispatcherInterface::class),
            $container->get(LoggerInterface::class),
            $container->get(Swift_SmtpTransport::class)
        );

        $mailer->registerPlugin(
            new Swift_Plugins_LoggerPlugin(
                new Logger(
                    $container->get(LoggerInterface::class),
                )
            )
        );

        return $mailer;
    }
];
