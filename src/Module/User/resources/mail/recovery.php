<?php

declare(strict_types=1);

use Yiisoft\Html\Html;

/** @var array $params */
?>

<p class = 'mail-recovery'>
    Hello,
</p>

<p class = 'mail-recovery'>
    We have received a request to reset the password for your account,

    Web Application Basic

    Please click the link below to complete your password reset.
</p>

<p class = 'mail-recovery'>
    <?= Html::a(Html::encode($params['url']), $params['url']) ?>
</p>

<p class = 'mail-recovery'>
    If you cannot click the link, please try pasting the text into your browser.
</p>

<p class = 'mail-recovery'>
    If you did not make this request you can ignore this email.
</p>
