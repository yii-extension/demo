<?php

declare(strict_types=1);

use Yiisoft\Html\Html;

/** @var array $params */
?>

<p class = 'mail-confirmation'>
    Hello, <?= $params['username'] ?>
</p>

<p class = 'mail-confirmation'>
    Thank you for signing up, Web Application App
    In order to complete your registration, please click the link below.
</p>

<p class = 'mail-confirmation'>
    <?= Html::a(Html::encode($params['url']), $params['url']) ?>
</p>

<p class = 'mail-confirmation'>
    If you cannot click the link, please try pasting the text into your browser.
</p>

<p class = 'mail-confirmation'>
    If you did not make this request you can ignore this email.
</p>
