<?php

declare(strict_types=1);

use Yiisoft\Html\Html;

/** @var array $params */
?>

<p class = 'mail-reconfirmation'>
    Hello
</p>

<p class = 'mail-reconfirmation'>
    We have received a request to change the email address for your account.

    In order to complete your request, please click the link below.
</p>

<p class = 'mail-reconfirmation'>
    <?= Html::a(Html::encode($params['url']), $params['url']) ?>
</p>

<p class = 'mail-reconfirmation'>
    If you cannot click the link, please try pasting the text into your browser.
</p>

<p class = 'mail-reconfirmation'>
    If you did not make this request you can ignore this email.
</p>
