<?php

declare(strict_types=1);

use Yiisoft\Html\Html;

/** @var array $params */
?>
Hello,

Your account has been created.

<?php if ($params['showPassword']) : ?>
    We have generated a password for you: <strong><?= $params['password'] ?></strong>
<?php endif ?>

<?php if (isset($params['url'])) : ?>
    <p class = 'mailer-welcome'>
        In order to complete your registration, please click the link below.
    </p>
    <p class = 'mailer-welcome'>
        <?= Html::a(Html::encode($params['url']), $params['url']) ?>
    </p>
    <p class = 'mailer-welcome'>
        If you cannot click the link, please try pasting the text into your browser.
    </p>
<?php endif ?>

If you did not make this request you can ignore this email.
