<?php

declare(strict_types=1);

/** @var array $params */
?>
Hello,

We have received a request to change the email address for your account on:
    <?= $params['email'] ?>

In order to complete your request, please click the link below.

<?= '<b>' . $params['url'] . '</b>' ?>

If you cannot click the link, please try pasting the text into your browser.
If you did not make this request you can ignore this email.
