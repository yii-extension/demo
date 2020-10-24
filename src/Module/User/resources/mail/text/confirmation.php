<?php

declare(strict_types=1);

/** @var array $params */
?>
Hello,

Thank you for signing up on <?= $params['username'] ?> .
    In order to complete your registration, please click the link below.

<?= '<b>' . $params['url'] . '</b>' ?>

If you cannot click the link, please try pasting the text into your browser.
If you did not make this request you can ignore this email.
