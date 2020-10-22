<?php

declare(strict_types=1);

/** @var array $params */
?>

<p class = 'mail-new_password'>
    Hello
</p>

<p class = 'mail-new_password'>
    Your account has a new password.

    We have generated a password for you:
        <?= '<b>' . $params['password'] . '</b>' ?>
</p>

<p class = 'mail-new_password'>
    If you did not make this request you can ignore this email.
</p>
