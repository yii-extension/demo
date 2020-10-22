<?php

declare(strict_types=1);

namespace Yii\Component;

use Swift_SmtpTransport;
use Psr\Container\ContainerInterface;
use Yii\Params;

$params = new Params();
$config = $params->getMailerConfig();

return [
    /** component swift mailer */
    Swift_SmtpTransport::class => static function (ContainerInterface $container) use ($config) {
        $swiftSmtpTransport = new Swift_SmtpTransport(
            $config['host'],
            $config['port'],
            $config['encryption']
        );

        $swiftSmtpTransport->setUsername($config['username']);
        $swiftSmtpTransport->setPassword($config['username']);

        return $swiftSmtpTransport;
    }
];
