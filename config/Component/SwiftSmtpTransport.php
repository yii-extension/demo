<?php

declare(strict_types=1);

namespace Yii\Component;

use Swift_SmtpTransport;

return [
    /** component swift mailer */
    Swift_SmtpTransport::class => [
        '__class' => Swift_SmtpTransport::class,
        '__construct()' => [
            $params['mailerConfig']['host'],
            $params['mailerConfig']['port'],
            $params['mailerConfig']['encryption']
        ],
        'setUsername()' => [$params['mailerConfig']['username']],
        'setPassword()' => [$params['mailerConfig']['username']]
    ]
];
