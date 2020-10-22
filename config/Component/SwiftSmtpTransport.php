<?php

declare(strict_types=1);

namespace Yii\Component;

use Swift_Transport;
use Yii\Params;

$params = new Params();
$config = $params->getMailerConfig();

return [
    /** component swift mailer */
    Swift_Transport::class => [
        '__class' => Swift_Transport::class,
        '__construct()' => [
            $config['host'],
            $config['port'],
            $config['encryption'],
        ],
        'setUsername()' => [$config['username']],
        'setPassword()' => [$config['username']]
    ],
];
